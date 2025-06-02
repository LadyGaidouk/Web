<?php
function loadEnvFile(string $filePath = '/etc/secrets/.env'): void {
    error_log('Проверка .env: существует=' . (file_exists($filePath) ? 'Да' : 'Нет'));
    error_log('Проверка .env: читаем=' . (is_readable($filePath) ? 'Да' : 'Нет'));
    error_log('Права .env: ' . (file_exists($filePath) ? substr(sprintf('%o', fileperms($filePath)), -4) : 'Файл не найден'));

    if (!file_exists($filePath)) {
        error_log('Ошибка: Файл .env не найден');
        exit("❌ Файл .env не найден");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        error_log('Ошибка: Не удалось прочитать файл .env');
        exit("❌ Не удалось прочитать файл .env");
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if (str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        putenv("$key=$value");
    }
}

// === Функции обработки полей формы ===
function getPostInput(string $key, string $default = 'Не указано'): string {
    return htmlspecialchars(trim($_POST[$key] ?? $default));
}

function getCheckboxGroup(string $key): string {
    return isset($_POST[$key]) ? implode(', ', array_map('htmlspecialchars', $_POST[$key])) : 'Не выбрано';
}

// === Инициализация ===
loadEnvFile();

$token   = getenv('BOT_API_TOKEN');
$chatId  = getenv('LADY_ID');

// Honeypot: защита от спама
if (!empty($_POST['email_confirm'])) {
    exit("❌ Ошибка: попытка отправки от бота");
}

// === Сбор данных ===
$username = getPostInput('username');
$contact  = getPostInput('contact');
$message  = getPostInput('message', 'Без мыслей');
$project  = getCheckboxGroup('project');
$budget   = getCheckboxGroup('budget');
$formTime = $_POST['form_timestamp'] ?? date('Y-m-d H:i:s');

// === Подготовка текста сообщения ===
$text = <<<MSG
📝 Новая заявка:

👤 Имя: $username
📞 Контакт: $contact
📌 Тип проекта: $project
💰 Бюджет: $budget
📅 Время: $formTime
💭 Сообщение: $message
MSG;

// === Отправка через Telegram Bot API ===
$sendUrl = "https://api.telegram.org/bot{$token}/sendMessage";

$response = file_get_contents($sendUrl . '?' . http_build_query([
    'chat_id' => $chatId,
    'text'    => $text,
]));

if ($response) {
    header("Location: thanku.php");
    exit;
}

exit("❌ Ошибка при отправке.");

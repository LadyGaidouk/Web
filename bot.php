<?php
ob_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0); // Отключаем ВСЕ отчеты об ошибках
ini_set('log_errors', 0); // Полностью отключаем логирование ошибок

// === Функции обработки ===
function getPostInput(string $key, string $default = 'Не указано'): string {
    return trim($_POST[$key] ?? $default); // Убрано htmlspecialchars
}

function getCheckboxGroup(string $name): string {
    if (empty($_POST[$name])) return 'Не указано';
    return is_array($_POST[$name]) 
        ? implode(', ', $_POST[$name]) 
        : $_POST[$name];
}

// === Инициализация ===
$token = getenv('BOT_API_TOKEN');
$chatId = getenv('LADY_ID');
}

// Honeypot + consent
if (!empty($_POST['email_confirm']) || !isset($_POST['consent'])) {
    header("Location: /error.php");
    exit;
}

// === Сбор данных с очисткой ===
$contact = getPostInput('contact');
$message = getPostInput('message', 'Без мыслей');
$project = getCheckboxGroup('project');
$budget = getCheckboxGroup('budget');
$formTime = $_POST['form_timestamp'] ?? date('Y-m-d H:i:s');

// === Формирование сообщения ===
$text = "Воу-воу, Леди\n📝 Получено новое сообщение через форму-посредник:\n\n"
      . "📞 Контакт: $contact\n"
      . "📌 Проект: $project\n"
      . "💰 Бюджет: $budget\n"
      . "📅 Время: $formTime\n"
      . "💭 Сообщение: $message";

// === Отправка через Telegram ===
$sendUrl = "https://api.telegram.org/bot{$token}/sendMessage?" . http_build_query([
    'chat_id' => $chatId,
    'text' => $text
]);

$context = stream_context_create(['http' => ['timeout' => 5]]);
$response = @file_get_contents($sendUrl, false, $context);

// === Глубокая очистка памяти ===
function deepClean() {
    foreach ($_POST as $key => $value) {
        if (is_array($value)) {
            array_walk_recursive($value, function(&$item) {
                $item = str_repeat('x', strlen($item));
            });
        }
        $_POST[$key] = str_repeat('x', strlen($value));
    }
    unset($_POST);
}
deepClean();

// Сессия уничтожается только если активна
if (session_status() === PHP_SESSION_ACTIVE) {
    $_SESSION = [];
    session_destroy();
}

if ($response !== false) {
    header("Location: /thanku.php");
}
exit;

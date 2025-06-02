<?php
ob_start(); // Включить буферизацию вывода
ini_set('display_errors', 0); // Отключить отображение ошибок
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', '/var/www/html/error.log');

// === Функции обработки полей формы ===
function getPostInput(string $key, string $default = 'Не указано'): string {
    return htmlspecialchars(trim($_POST[$key] ?? $default));
}

function getCheckboxGroup(string $key): string {
    return isset($_POST[$key]) ? implode(', ', array_map('htmlspecialchars', $_POST[$key])) : 'Не выбрано';
}

// === Инициализация ===
$token = getenv('BOT_API_TOKEN');
$chatId = getenv('LADY_ID');

if (!$token || !$chatId) {
    error_log('Ошибка: BOT_API_TOKEN или LADY_ID не заданы в переменных окружения');
    exit("❌ Ошибка сервера: конфигурация не найдена");
}

// Honeypot: защита от спама
if (!empty($_POST['email_confirm'])) {
    exit("❌ Ошибка: попытка отправки от бота");
}

// === Сбор данных ===
$username = getPostInput('username');
$contact = getPostInput('contact');
$message = getPostInput('message', 'Без мыслей');
$project = getCheckboxGroup('project');
$budget = getCheckboxGroup('budget');
$formTime = $_POST['form_timestamp'] ?? date('Y-m-d H:i:s');

// === Подготовка текста сообщения ===
$text = <<<MSG
Воу-воу, Леди
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
    'text' => $text,
]));

if ($response) {
    header("Location: /thanku.php");
    exit;
}

error_log('Ошибка отправки в Telegram: ' . $response);
exit("❌ Ошибка при отправке.");
?>

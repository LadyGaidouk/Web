<?php
ob_start();
session_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', '/var/www/html/error.log');

file_put_contents('/var/www/html/error.log', "DEBUG: bot.php started at " . date('Y-m-d H:i:s') . " with method " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    file_put_contents('/var/www/html/error.log', "ERROR: Invalid request method " . $_SERVER['REQUEST_METHOD'] . " at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    http_response_code(405);
    ob_end_clean();
    exit("Method not allowed");
}

function getPostInput(string $key, string $default = 'Не указано'): string {
    return htmlspecialchars(trim($_POST[$key] ?? $default));
}

function getCheckboxGroup(string $name): string {
    if (empty($_POST[$name])) return 'Не указано';
    return is_array($_POST[$name]) ? implode(', ', array_map('htmlspecialchars', $_POST[$name])) : htmlspecialchars($_POST[$name]);
}

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    file_put_contents('/var/www/html/error.log', "ERROR: Invalid CSRF token at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    http_response_code(403);
    ob_end_clean();
    exit("Invalid CSRF token");
}

$token = getenv('BOT_API_TOKEN');
$chatId = getenv('LADY_ID');

file_put_contents('/var/www/html/error.log', "DEBUG: BOT_API_TOKEN=" . ($token ? 'set' : 'unset') . ", LADY_ID=" . ($chatId ? 'set' : 'unset') . " at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

if (!$token || !$chatId) {
    file_put_contents('/var/www/html/error.log', "ERROR: Missing BOT_API_TOKEN or LADY_ID at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    http_response_code(500);
    ob_end_clean();
    exit("Server configuration error");
}

if (!empty($_POST['email_confirm'])) {
    file_put_contents('/var/www/html/error.log', "ERROR: Bot attempt detected at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    http_response_code(403);
    ob_end_clean();
    exit("Bot attempt detected");
}

if (!isset($_POST['consent'])) {
    file_put_contents('/var/www/html/error.log', "ERROR: Consent not provided at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    http_response_code(400);
    ob_end_clean();
    exit("Consent required");
}

$contact = getPostInput('contact');
$message = getPostInput('message', 'Без мыслей');
$project = getCheckboxGroup('project');
$budget = getCheckboxGroup('budget');
$formTime = $_POST['form_timestamp'] ?? date('Y-m-d H:i:s');

file_put_contents('/var/www/html/error.log', "DEBUG: Form data - contact='$contact', message='$message', project='$project', budget='$budget', time='$formTime' at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

$text = "Воу-воу, Леди\n📝 Новое сообщение через форму-посредник:\n\n"
      . "📞 Контакт: $contact\n"
      . "📌 Проект: $project\n"
      . "💰 Бюджет: $budget\n"
      . "📅 Время: $formTime\n"
      . "💭 Сообщение: $message";

$ch = curl_init("https://api.telegram.org/bot{$token}/sendMessage");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'chat_id' => $chatId,
    'text' => $text,
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
$response = curl_exec($ch);
$curlError = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false || !empty($curlError) || $httpCode >= 400) {
    file_put_contents('/var/www/html/error.log', "ERROR: Telegram API failed with error '$curlError', HTTP $httpCode at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    http_response_code(500);
    ob_end_clean();
    exit("Telegram API error");
}

$responseData = json_decode($response, true);
if (!$responseData || !isset($responseData['ok']) || !$responseData['ok']) {
    file_put_contents('/var/www/html/error.log', "ERROR: Telegram API response invalid: " . $response . " at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    http_response_code(500);
    ob_end_clean();
    exit("Telegram API response error");
}

function deepClean() {
    foreach ($_POST as $key => $value) {
        if (is_array($value)) {
            array_walk_recursive($value, function(&$item) {
                $item = str_repeat('x', strlen((string)$item));
            });
        } else {
            $_POST[$key] = str_repeat('x', strlen((string)$value));
        }
    }
    unset($_POST);
}
deepClean();

$_SESSION = [];
session_destroy();

file_put_contents('/var/www/html/error.log', "DEBUG: Message sent to Telegram at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
header("Location: https://tech.gaidouk.ru/thanku.php");
ob_end_flush();
exit;
?>

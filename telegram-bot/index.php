<?php

require "send.php";

$raw = file_get_contents('php://input');
$logEntry = sprintf("%s raw=%s\n", date('c'), json_encode($raw));
file_put_contents(__DIR__ . '/telegram-debug.log', $logEntry, FILE_APPEND | LOCK_EX);

$update = json_decode($raw, true);

if (empty($update)) {
    // If the file is opened in a browser, send a quick test message to the configured chat.
    sendMessage(CHAT_ID, "🚀 CI/CD Bot is working successfully!");
    exit;
}

$message = $update['message'] ?? $update['edited_message'] ?? $update['callback_query']['message'] ?? null;
$chat_id = $message['chat']['id'] ?? null;
$text = trim($message['text'] ?? $update['callback_query']['data'] ?? '');

if (!$chat_id || $text === '') {
    exit;
}

if (strtolower($text) === '/start') {
    $reply = "Hello! I am your Telegram bot. Send any message and I'll echo it back.";
} else {
    $reply = "You said: " . $text;
}

sendMessage($chat_id, $reply);
?>
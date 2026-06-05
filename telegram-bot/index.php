<?php

require "send.php";

$raw = file_get_contents('php://input');
$update = json_decode($raw, true);

if (empty($update)) {
    // If the file is opened in a browser, send a quick test message to the configured chat.
    sendMessage(CHAT_ID, "🚀 CI/CD Bot is working successfully!");
    exit;
}

$chat_id = $update['message']['chat']['id'] ?? null;
$text = trim($update['message']['text'] ?? '');

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
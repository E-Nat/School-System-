<?php

require "config.php";

function sendMessage($chat_id, $message) {
    $url = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage";

    $payload = json_encode([
        "chat_id" => $chat_id,
        "text" => $message,
        "parse_mode" => "HTML",
        "disable_web_page_preview" => true
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    $result = curl_exec($ch);
    $curlError = curl_error($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $log = sprintf(
        "%s sendMessage(chat_id=%s status=%s error=%s payload=%s result=%s\n",
        date('c'),
        json_encode($chat_id),
        $status,
        json_encode($curlError),
        json_encode($payload),
        json_encode($result)
    );
    file_put_contents(__DIR__ . '/telegram-debug.log', $log, FILE_APPEND | LOCK_EX);

    return $result;
}
?>
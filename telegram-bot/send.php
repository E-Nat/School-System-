<?php

require "config.php";

function sendMessage($message) {
    $url = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage";

    $data = [
        "chat_id" => CHAT_ID,
        "text" => $message
    ];

    $options = [
        "http" => [
            "header"  => "Content-Type: application/x-www-form-urlencoded",
            "method"  => "POST",
            "content" => http_build_query($data),
        ]
    ];

    $context = stream_context_create($options);
    file_get_contents($url, false, $context);
}
?>
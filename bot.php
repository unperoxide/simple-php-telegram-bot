<?php

/**
 * Delete Join/Leave message in telegram group
 * Add to your group as admin
 */

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('error_log', __DIR__ . '/logs/error/php-error.log');

define('BOT_TOKEN', '<BOT_TOKEN>');
define('API_URL', 'https://api.telegram.org/bot' . BOT_TOKEN);

$update  = json_decode(file_get_contents('php://input'), true);
$message = $update['message'] ?? $update['channel_post'] ?? '';

if (empty($message)) exit;

// Delete group join/left message
if (isset($message['new_chat_member']) || isset($message['left_chat_member'])) {
    @file_get_contents(API_URL . '/deleteMessage?' . http_build_query([
        'chat_id' => $message['chat']['id'],
        'message_id' => $message['message_id'],
    ]));
    exit;
}

// Display input for development
if (strpos(@$message['text'], '/info') === 0) {
    file_get_contents(API_URL . '/sendMessage?' . http_build_query([
        'chat_id' => $message['chat']['id'],
        'text' => '<pre>' . print_r($message, true) . '</pre>',
        'parse_mode' => 'html',
    ]));
    exit;
}

// Start
if (strpos(@$message['text'], '/start') === 0) {
    file_get_contents(API_URL . '/sendMessage?' . http_build_query([
        'chat_id' => $message['chat']['id'],
        'text' => '🙋🏻‍♂️ ' . ($message['chat']['first_name'] ?? 'User') . ', this is a bot!',
    ]));
    exit;
}

// Hi
if (in_array(strtolower(@$message['text']), ['hi', 'hello', 'hey'], true)) {
    file_get_contents(API_URL . '/sendMessage?' . http_build_query([
        'chat_id' => $message['chat']['id'],
        'text' => '🙋🏻‍♂️ ' . ($message['chat']['first_name'] ?? 'User'),
    ]));
    exit;
}

// Help
if (strpos(@$message['text'], '/help') === 0) {
    file_get_contents(API_URL . '/sendMessage?' . http_build_query([
        'chat_id' => $message['chat']['id'],
        'text' => '?',
    ]));
    exit;
}

// Dev
if (strpos(@$message['text'], '/dev') === 0) {
    file_get_contents(API_URL . '/sendMessage?' . http_build_query([
        'chat_id' => $message['chat']['id'],
        'text' => '👨🏻‍💻 Developed by @unperoxide',
    ]));
    exit;
}

// Default
if (isset($message['text']) && @$message['chat']['type'] === 'private') {
    file_get_contents(API_URL . '/sendMessage?' . http_build_query([
        'chat_id' => $message['chat']['id'],
        'text' => '🦄 /help for available commands.',
    ]));
    exit;
}

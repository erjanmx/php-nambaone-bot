<?php
require __DIR__ . '/../vendor/autoload.php';

use erjanmx\nambaone\Client;
use erjanmx\nambaone\ClientException;

$API_KEY = '';
try {
    $client = new Client($API_KEY);

    $client->command('on/follow', function ($user) use ($client) {
        $chat = $client->api->createChat($user['id']);

        $client->to($chat->data->id)->send('Welcome');
    });

    $client->command('message/new', function ($message) use ($client) {
        $client->to($message['chat_id'])->setType($message['type'])->send($message['content']);
    });

    $client->handleEvents();
} catch (ClientException $e) {
    //handle exception
}

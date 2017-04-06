<?php
/*
 * Example that downloads media files sent by user and replies with the default media file
 */

require __DIR__ . '/../vendor/autoload.php';

use erjanmx\nambaone\Client;
use erjanmx\nambaone\Message;
use erjanmx\nambaone\ClientException;

$API_KEY = '';
try {
    $client = new Client($API_KEY);

    $token = $client->api->uploadFile('PATH_TO_IMAGE');

    $media = new Message($client);
    $media->setType(Message::CONTENT_TYPE_MEDIA_IMAGE);
    $media->setContent($token);

    $client->command('message/new', function ($message) use ($client, $media) {

        $client->to($message['chat_id'])->setType($media->getType())->send($media->getContent());
        /* or */
        $client->api->sendMessage($media->setChatId($message['chat_id']));
        
        // if user sent media download it
        if ($message['type'] != Message::CONTENT_TYPE_TEXT_PLAIN) {
            $file_content = $client->api->getFile($message['content']);
        }
    });

    $client->handleEvents();
} catch (ClientException $e) {
    //handle exception
}

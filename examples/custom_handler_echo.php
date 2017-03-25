<?php
require __DIR__ . '/../vendor/autoload.php';

use erjanmx\nambaone\Client;
use erjanmx\nambaone\EventsHandler;
use erjanmx\nambaone\ClientException;

/**
 * @class
 */
class CustomEventsHandler extends EventsHandler
{
    public $events = [
        'message/new' => 'messageNew',
    ];

    public function messageNew($data)
    {
        $this->client->to($data['chat_id'])->setType($data['type'])->setContent($data['content'])->send();
    }
}

$API_KEY = '';
try {
    $client = new Client($API_KEY, new CustomEventsHandler());

    $client->handleEvents();
} catch (ClientException $e) {
    //handle exception
}

<?php

namespace erjanmx\nambaone;

use Closure;
use GuzzleHttp\Client as Guzzle;
use InvalidArgumentException;

class Api
{
    protected $api_url = 'https://api.namba1.co';

    protected $token;

    protected $guzzle;

    
    function __construct($token)
    {
        $this->token = $token;

        $this->guzzle = new Guzzle(['base_uri' => $this->api_url]);
    }

    protected function getHeaders()
    {
        return [
            'X-Namba-Auth-Token' => $this->token
        ];
    }

    public function createChat($user_id, $name = '', $image = '')
    {
        $headers = $this->getHeaders();

        $form_params = array_merge(
            compact('name', 'image'), [
                'members[]' => $user_id
            ]
        );

        $options = compact('form_params', 'headers');

        return json_decode($this->guzzle->post('/chats/create', $options)->getBody());
    }

    public function sendMessage(Message $message)
    {
        $headers = $this->getHeaders();
        $form_params = [
            'type' => $message->getType(),
            'content' => $message->getContent(),
        ];

        $options = compact('form_params', 'headers');

        $request = $this->guzzle->post(
            sprintf('/chats/%d/write', $message->getChatId())
        , $options);

        return json_decode($request->getBody());
    }
}

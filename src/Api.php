<?php

namespace erjanmx\nambaone;

use Closure;
use GuzzleHttp\Client as Guzzle;
use InvalidArgumentException;

class Api
{
    /**
     * Api endpoint
     * 
     * @var string
     */
    protected $api_url = 'https://api.namba1.co';

    /**
     * @var Api token
     */
    protected $token;

    /**
     * @var Guzzle http client
     */
    protected $guzzle;

    /**
     * Api constructor.
     * 
     * @param $token
     */
    function __construct($token)
    {
        $this->token = $token;

        $this->guzzle = new Guzzle(['base_uri' => $this->api_url]);
    }

    /**
     * Prepare Auth-token
     * 
     * @return array
     */
    protected function getHeaders()
    {
        return [
            'X-Namba-Auth-Token' => $this->token
        ];
    }

    /**
     * Chat creation api call
     * 
     * @param $user_id
     * @param string $name
     * @param string $image
     * @return mixed
     */
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

    /**
     * Message sending api call
     * 
     * @param Message $message
     * @return mixed
     */
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

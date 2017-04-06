<?php

namespace erjanmx\nambaone;

use Closure;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\BadResponseException;
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
     * File managing endpoint
     *
     * @var string
     */
    protected $api_url_files = 'https://files.namba1.co';

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

    /**
     * Gets content of uploaded file by its token
     *
     * @param Message $message
     * @return string|json
     */
    public function getFile($token)
    {
        $response = $this->guzzle->get($this->api_url_files, [
            'query' => compact('token'),
        ])->getBody();
        
        json_decode($response);
        
        if (json_last_error() == JSON_ERROR_NONE) {
            throw new ClientException('File not found');
        }
        
        return $response;
    }

    /**
     * Uploads file to api endpoint and returns its token
     *
     * @param $filename
     * @return string|null
     */
    public function uploadFile($filename)
    {
        if (! file_exists($filename)) {
            throw new \BadMethodCallException('File not exists');
        }
        
        $response = json_decode($this->guzzle->post($this->api_url_files, [
             'multipart' => [
                 [
                    'name' => 'file',
                    'contents' => fopen($filename, 'r')
                 ]
             ]
        ])->getBody());

        if ((isset($response->success)) && ($response->success)) {
            return $response->file;
        } else {
            throw new ClientException('File is not uploaded');
        }
    }
}

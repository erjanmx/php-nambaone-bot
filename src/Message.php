<?php

namespace erjanmx\nambaone;


class Message
{
    const CONTENT_TYPE_AUDIO_MP4 = 'audio/mp4';
    const CONTENT_TYPE_TEXT_PLAIN = 'text/plain';
    const CONTENT_TYPE_MEDIA_IMAGE = 'media/image';
    const CONTENT_TYPE_CONTACT_JSON = 'contact/json';
    const CONTENT_TYPE_LOCATION_JSON = 'location/json';

    /**
     * @var Client
     */
    protected $client;

    protected $type;

    protected $content;

    protected $chat_id;


    function __construct(Client $client)
    {
        $this->client = $client;
        
        $this->setType(self::CONTENT_TYPE_TEXT_PLAIN);
    }

    public function to($chat_id)
    {
        $this->setChatId($chat_id);

        return $this;
    }

    public function setChatId($chat_id)
    {
        $this->chat_id = $chat_id;

        return $this;
    }

    public function getChatId()
    {
        return $this->chat_id;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function send($content = null)
    {
        if (! is_null($content))
            $this->setContent($content);

        return $this->client->api->sendMessage($this);
    }
}

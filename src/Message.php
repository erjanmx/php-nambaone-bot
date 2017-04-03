<?php

namespace erjanmx\nambaone;

class Message
{
    const CONTENT_TYPE_AUDIO_MP4 = 'audio/mp4';
    const CONTENT_TYPE_TEXT_PLAIN = 'text/plain';
    const CONTENT_TYPE_MEDIA_IMAGE = 'media/image';
    const CONTENT_TYPE_CONTACT_JSON = 'contact/json';
    const CONTENT_TYPE_STICKER_JSON = 'sticker/json';
    const CONTENT_TYPE_LOCATION_JSON = 'location/json';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string Message type
     */
    protected $type;

    /**
     * @var string Message content
     */
    protected $content;

    /**
     * @var integer 
     */
    protected $chat_id;

    /**
     * Message constructor.
     * @param Client $client
     */
    function __construct(Client $client)
    {
        $this->client = $client;
        
        $this->setType(self::CONTENT_TYPE_TEXT_PLAIN);
    }

    /**
     * @param $chat_id
     * @return $this
     */
    public function to($chat_id)
    {
        $this->setChatId($chat_id);

        return $this;
    }

    /**
     * @param $chat_id
     * @return $this
     */
    public function setChatId($chat_id)
    {
        $this->chat_id = $chat_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getChatId()
    {
        return $this->chat_id;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param null $content
     * @return mixed
     */
    public function send($content = null)
    {
        if (! is_null($content))
            $this->setContent($content);

        return $this->client->api->sendMessage($this);
    }
}

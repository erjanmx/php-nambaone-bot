<?php

namespace erjanmx\nambaone;

use Closure;
use GuzzleHttp\Client as Guzzle;
use InvalidArgumentException;

class Client
{
    /**
     * @var Api 
     */
    public $api;

    /**
     * @var EventsHandler
     */
    protected $events_handler;

    /**
     * Client constructor
     * 
     * @param $token - api token
     * @param null $events_handler
     */
    function __construct($token, $events_handler = null)
    {
        if (is_null($events_handler)) {
            $events_handler = new EventsHandler();
        } elseif (! $events_handler instanceof EventsHandler) {
            throw new InvalidArgumentException('Second parameter must be instance of EventsHandler');
        }

        $this->events_handler = $events_handler;
        $this->events_handler->setClient($this);

        $this->api = new Api($token);
    }

    /**
     * Missing methods goes through Message
     * 
     * @param $name
     * @param $arguments
     * @return Message
     */
    public function __call($name, $arguments)
    {
        $message = new Message($this);

        call_user_func_array([$message, $name], $arguments);

        return $message;
    }

    /**
     * Assign event handler
     * 
     * @param $event
     * @param Closure $action
     * @return $this
     */
    public function command($event, Closure $action)
    {
        $this->events_handler->assign($event, $action);

        return $this;
    }

    /**
     * Handle incoming hooks from api 
     */
    public function handleEvents()
    {
        $post = json_decode(file_get_contents('php://input'), true);

        $this->events_handler->run($post['event'], $post['data']);
    }

    /**
     * @return EventsHandler
     */
    public function getEventsHandler()
    {
        return $this->events_handler;
    }
}

<?php

namespace erjanmx\nambaone;

use Closure;

class EventsHandler
{
    /**
     * List of events name and its handlers
     * 
     * @var array
     */
    public $events = [];

    /**
     * @var Client
     */
    protected $client;

    /**
     * Handle api hooks
     * 
     * @param $name
     * @param $data
     */
    public function run($name, $data)
    {
        if (! array_key_exists($name, $this->events))
            throw new \BadMethodCallException;

        if (is_callable($this->events[$name]))
            call_user_func($this->events[$name], $data);
        else
            call_user_func([$this, $this->events[$name]], $data);
    }

    /**
     * Assign api hook handler
     * 
     * @param $name
     * @param Closure $closure
     * @return $this
     */
    public function assign($name, Closure $closure)
    {
        $this->events[$name] = $closure;

        return $this;
    }

    /**
     * @param $client
     * @return $this
     */
    public function setClient($client)
    {
        $this->client = $client;
        
        return $this;
    }
}

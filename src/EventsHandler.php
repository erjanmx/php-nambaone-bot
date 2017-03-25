<?php

namespace erjanmx\nambaone;

use Closure;

class EventsHandler
{
    public $events = [];
    
    protected $client;


    public function run($name, $data)
    {
        if (! array_key_exists($name, $this->events))
            throw new \BadMethodCallException;

        if (is_callable($this->events[$name]))
            call_user_func($this->events[$name], $data);
        else
            call_user_func([$this, $this->events[$name]], $data);
    }

    public function assign($name, Closure $closure)
    {
        $this->events[$name] = $closure;

        return $this;
    }
    
    public function setClient($client)
    {
        $this->client = $client;
        
        return $this;
    }
}

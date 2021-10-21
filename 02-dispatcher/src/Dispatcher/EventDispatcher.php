<?php

namespace App\Dispatcher;

class EventDispatcher
{
    private array $events = [];

    public function addListener(string $eventName, callable $fn)
    {
        if (empty($this->events[$eventName])) {
            $this->events[$eventName] = [];
        }

        $this->events[$eventName][] = $fn;
    }

    public function dispatch(string $eventName, $eventData = null)
    {
        if (empty($this->events[$eventName])) {
            return;
        }

        $functions = $this->events[$eventName];

        foreach ($functions as $fn) {
            $fn($eventData);
        }
    }

    public function addSubscriber(string $className)
    {
        $instance = new $className();

        foreach ($className::getSubscribedEvents() as $eventName => $methodName) {
            $this->addListener($eventName, [$instance, $methodName]);
        }
    }
}

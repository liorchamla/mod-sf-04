<?php

namespace Container;

class Container
{
    private array $factories = [];

    public function set(string $className, callable $factory)
    {
        $this->factories[$className] = $factory;
    }

    public function get(string $className)
    {
        $factory = $this->factories[$className];

        $instance = $factory($this);

        return $instance;
    }
}

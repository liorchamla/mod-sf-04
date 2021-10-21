<?php

namespace Container;

use ReflectionClass;

class Container
{
    private array $factories = [];
    private array $instances = [];

    public function set(string $className, callable $factory)
    {
        $this->factories[$className] = $factory;
    }

    public function get(string $className)
    {
        if (!empty($this->instances[$className])) {
            return $this->instances[$className];
        }

        if (!empty($this->factories[$className])) {
            $factory = $this->factories[$className];

            $instance = $factory($this);

            $this->instances[$className] = $instance;

            return $instance;
        }

        $reflection = new ReflectionClass($className); // Migrations\MigrationsRunner

        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            $instance = new $className();

            $this->instances[$className] = $instance;

            return $instance;
        }

        $parameters = $constructor->getParameters();

        $arguments = [];

        foreach ($parameters as $param) {
            $paramClassName = $param->getType()->getName();

            $instance = $this->get($paramClassName);

            $arguments[] = $instance;
        }

        $instance = $reflection->newInstanceArgs($arguments);

        $this->instances[$className] = $instance;

        return $instance;
    }
}

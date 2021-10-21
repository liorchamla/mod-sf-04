<?php

use Migrations\Logger;
use Migrations\MigrationsRunner;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/container.php';

$runner = $container->get(MigrationsRunner::class);

var_dump($runner);

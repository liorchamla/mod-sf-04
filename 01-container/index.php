<?php

use Migrations\Logger;
use Migrations\MigrationsRunner;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/container.php';

$logger = $container->get(Logger::class);
$logger->log("Hello, ça marche");

$runner = $container->get(MigrationsRunner::class);

var_dump($runner);

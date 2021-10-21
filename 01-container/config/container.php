<?php

use Container\Container;
use Migrations\History;
use Migrations\Logger;
use Migrations\MigrationsFinder;
use Migrations\MigrationsRunner;
use Migrations\SqlRunner;

$container = new Container;

$container->set(PDO::class, function () {
    $pdo = new PDO("mysql:host=localhost;dbname=migrations_test", "root", "root");
    return $pdo;
});

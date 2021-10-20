<?php

use Container\Container;
use Migrations\History;
use Migrations\Logger;
use Migrations\MigrationsFinder;
use Migrations\MigrationsRunner;
use Migrations\SqlRunner;

$container = new Container;

$container->set(Logger::class, function () {
    $logger = new Logger;
    return $logger;
});

$container->set(SqlRunner::class, function (Container $c) {
    $pdo = $c->get(PDO::class);
    $logger = $c->get(Logger::class);
    $sqlRunner = new SqlRunner($pdo, $logger);

    return $sqlRunner;
});

$container->set(MigrationsFinder::class, function (Container $c) {
    $history = $c->get(History::class);
    $finder = new MigrationsFinder($history);

    return $finder;
});

$container->set(MigrationsRunner::class, function (Container $c) {
    $logger = $c->get(Logger::class);
    $history = $c->get(History::class);
    $finder = $c->get(MigrationsFinder::class);
    $sqlRunner = $c->get(SqlRunner::class);

    $runner = new MigrationsRunner($history, $finder, $sqlRunner, $logger);

    return $runner;
});

$container->set(PDO::class, function () {
    $pdo = new PDO("mysql:host=localhost;dbname=migrations_test", "root", "root");
    return $pdo;
});

$container->set(History::class, function (Container $c) {
    $pdo = $c->get(PDO::class);
    $history = new History($pdo);

    return $history;
});

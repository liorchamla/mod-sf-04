<?php

use Migrations\History;
use Migrations\Logger;
use Migrations\MigrationsFinder;
use Migrations\MigrationsRunner;
use Migrations\SqlRunner;

require_once __DIR__ . '/vendor/autoload.php';

$pdo = new PDO("mysql:host=localhost;dbname=migrations_test", "root", "root");
$logger = new Logger;
$history = new History($pdo);
$finder = new MigrationsFinder($history);
$sqlRunner = new SqlRunner($pdo, $logger);

$runner = new MigrationsRunner($history, $finder, $sqlRunner, $logger);

// $runner->run(__DIR__ . '/migrations');

var_dump($runner);

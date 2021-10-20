<?php

use Migrations\History;
use Migrations\Logger;
use Migrations\MigrationsFinder;
use Migrations\MigrationsRunner;
use Migrations\SqlRunner;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MigrationsRunnerTest extends TestCase
{
    public function test_it_finds_all_my_migrations_files()
    {
        /** @var PDO|MockObject */
        $pdo = $this->createMock(PDO::class);
        $history = new History($pdo);
        $finder = new MigrationsFinder($history);

        $files = $finder->getMigrationsFiles(__DIR__ . '/migrations');

        $this->assertIsArray($files);
        $this->assertCount(2, $files);
    }

    public function test_it_can_find_last_migration()
    {
        /** @var PDO|MockObject */
        $pdo = $this->createMock(PDO::class);
        $pdo->method('query')->willReturn(new Result(['version' => 'M2']));

        $history = new History($pdo);

        $this->assertEquals("M2", $history->getLastMigration());
    }

    public function test_it_returns_null_if_no_migrations_was_done()
    {
        /** @var PDO|MockObject */
        $pdo = $this->createMock(PDO::class);
        $pdo->method('query')->willReturn(new Result(false));

        $history = new History($pdo);

        $this->assertEquals(null, $history->getLastMigration());
    }

    public function test_it_can_find_unplayed_migrations()
    {
        /** @var PDO */
        $pdo = $this->createMock(PDO::class);

        $history = new History($pdo);

        $unplayed = $history->getUnplayedMigrations([
            "Migration1.php",
            "Migration2.php",
            "Migration3.php",
            "Migration4.php"
        ], "Migration1");


        $this->assertEquals([
            "Migration2.php",
            "Migration3.php",
            "Migration4.php"
        ], $unplayed);
    }

    public function test_that_the_sql_query_is_ok_for_table_creation()
    {
        /** @var PDO|MockObject */
        $pdo = $this->createMock(PDO::class);

        $pdo->expects($this->once())
            ->method('query')
            ->with("CREATE TABLE IF NOT EXISTS migrations_status (version VARCHAR(255))");

        $history = new History($pdo);

        $history->createMigrationsTable();
    }

    public function test_it_runs_migrations_sql()
    {
        /** @var PDO|MockObject */
        $pdo = $this->createMock(PDO::class);

        /** @var Logger|MockObject */
        $logger = $this->createMock(Logger::class);
        $logger->expects($this->exactly(2))->method('log');

        $sqlRunner = new SqlRunner($pdo, $logger);

        $pdo->expects($this->exactly(2))
            ->method('query')
            ->withConsecutive(["CREATE TABLE toto"], ["CREATE TABLE tata"]);

        $m1 = $this->createMock(Migration::class);
        $m1->method('export')->willReturn("CREATE TABLE toto");

        $m2 = $this->createMock(Migration::class);
        $m2->method('export')->willReturn("CREATE TABLE tata");

        $objects = [$m1, $m2];

        $sqlRunner->runSqlFromMigrations($objects);
    }
}

class Migration
{
    public function export()
    {
    }
}

class Result
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function fetch()
    {
        return $this->value;
    }
}

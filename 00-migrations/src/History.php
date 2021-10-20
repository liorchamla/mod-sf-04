<?php

namespace Migrations;

use PDO;

class History
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createMigrationsTable()
    {
        $this->pdo->query("CREATE TABLE IF NOT EXISTS migrations_status (version VARCHAR(255))");
    }

    public function getUnplayedMigrations(array $files, string $lastMigration = null)
    {
        $unplayed = $files;

        if ($lastMigration) {
            $classes = array_map(function ($file) {
                return basename($file, '.php');
            }, $files);

            $index = array_search($lastMigration, $classes);

            if ($index === false) {
                return $files;
            }

            $unplayed = array_slice($files, $index + 1);
        }

        return $unplayed;
    }

    public function getLastMigration(): ?string
    {
        $result = $this->pdo->query("SELECT * FROM migrations_status");
        $data = $result->fetch();

        if (!$data) {
            return null;
        }

        return $data['version'];
    }

    public function updateLastMigration(string $lastMigrationClass)
    {
        $this->pdo->query('DELETE FROM migrations_status');
        $this->pdo->query("INSERT INTO migrations_status SET version = '$lastMigrationClass'");
    }
}

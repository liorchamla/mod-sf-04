<?php

namespace Migrations;

use PDO;

class SqlRunner
{
    private PDO $pdo;
    private Logger $logger;

    public function __construct(PDO $pdo, Logger $logger)
    {
        $this->pdo = $pdo;
        $this->logger = $logger;
    }

    public function runSqlFromMigrations(array $objects)
    {
        $className = null;

        foreach ($objects as $object) {
            $sql = $object->export();

            $className = get_class($object);

            $this->logger->log("$className : $sql\n");

            $this->pdo->query($sql);
        }

        return $className;
    }
}

<?php

namespace Migrations;

/**
 * Permet de retrouver les fichiers de migration dans un dossier donné puis de ne créer que les instances qui n'ont pas encore été jouées
 * 
 * @package Migrations
 */
class MigrationsFinder
{
    private History $history;

    public function __construct(History $history)
    {
        $this->history = $history;
    }

    public function getMigrationsFiles(string $path): array
    {
        $files = glob($path . '/*.php');
        return $files;
    }

    public function createMigrationsInstances(string $path)
    {
        $lastMigration = $this->history->getLastMigration();

        $unplayed = $this->history->getUnplayedMigrations(
            $this->getMigrationsFiles($path),
            $lastMigration
        );

        $objects = [];

        foreach ($unplayed as $file) {
            // 1. Je require :
            require_once $file;

            // 2. Je veux instancier la classe
            $className = basename($file, ".php");

            $object = new $className();

            $objects[] = $object;
        }

        return $objects;
    }
}

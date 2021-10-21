<?php

namespace Migrations;

use PDO;

class MigrationsRunner
{
    private History $history;
    private MigrationsFinder $finder;
    private SqlRunner $sqlRunner;
    private Logger $logger;

    /**
     * @param History $history Permet de faire l'historique des migrations déjà jouées ou pas
     * @param MigrationsFinder $finder Permet de trouver les fichiers de migration et de créer les instances nécessaires
     * @param SqlRunner $sqlRunner Permet de jouer les requêtes de chaque migration
     * @param Logger $logger Permet de logger les opérations
     */
    public function __construct(
        History $history,
        MigrationsFinder $finder,
        SqlRunner $sqlRunner,
        Logger $logger,
    ) {
        $this->history = $history;
        $this->finder = $finder;
        $this->sqlRunner = $sqlRunner;
        $this->logger = $logger;
    }

    public function run(string $path)
    {
        $this->logger->log("\nLancement des migrations ...\n");

        // Créer la table qui permettra de sauver la dernière migration
        $this->history->createMigrationsTable();

        $objects = $this->finder->createMigrationsInstances($path);

        if (empty($objects)) {
            $this->logger->log("Aucune migration à jouer\n");
        } else {
            $lastMigrationClass = $this->sqlRunner->runSqlFromMigrations($objects);

            $this->history->updateLastMigration($lastMigrationClass);
        }

        $this->logger->log("Fin des migrations !");
    }
}

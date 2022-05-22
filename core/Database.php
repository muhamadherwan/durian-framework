<?php

namespace app\core;

class Database
{

    public \PDO $pdo;

    /**
     * connection to mysql database
     * @param array $config
     */
    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Migrations process
     * @return void
     */
    public function applyMigrations()
    {
        $this->createMigrationsTable();

        // get all existing migrations in db
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];

        // get existing migrations files
        $files = scandir(Application::$ROOT_DIR.'/migrations');

        // get migration that have not been applied.
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        // start migration process
        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..'){
                continue;
            }

            // get the migrations file
            require_once Application::$ROOT_DIR.'/migrations/'.$migration;

            // get the migration file name and set as the migrations class name.
            $className = pathinfo($migration, PATHINFO_FILENAME);

            // init the new migration class
            $instance = new $className();
            $this->log("Applying migration $migration");

            // execute new migration sql statement
            $instance->up();
            $this->log("Applied migration $migration");

            $newMigrations [] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("All migrations are applied");
        }

    }

    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            crated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;");
    }

    /**
     * Get all migration from db
     * @return array|false
     */
    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigrations(array $migrations)
    {
        // combine all the new migration file in one string.
        $str = implode(",", array_map(fn($m) => "('$m')", $migrations));

        // save in table
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
        $statement->execute();

    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    protected function log($message)
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }
}
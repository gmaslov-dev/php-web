<?php

namespace PhpWeb\Database;

class Database
{
    private string $host;
    private string $username;
    private string $password;
    private string $dbname;
    private ?\PDO $pdo;

    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'];
        $this->username = $_ENV['DB_USERNAME'];
        $this->password = $_ENV['DB_PASSWORD'];
        $this->dbname = $_ENV['DB_NAME'];

        $this->connect();
    }

    private function connect(): void
    {
        try {
            $dsn = "pgsql:host=postgres;port=5432;dbname=$this->dbname";
            $this->pdo = new \PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            dump("Connected to PostgreSQL");
        } catch (\PDOException $e) {
            echo "❌ Cannot connect to PostgreSQL: " . $e->getMessage();
        }
    }

    public function createDatabase(): void
    {
       if (!$this->dbExists()) {
           echo "create Database: $this->dbname\n";
           $stmt = $this->pdo->prepare("CREATE DATABASE $this->dbname");
           $stmt->execute();
           $this->pdo = null;
           $this->connect();
       }
    }

    private function dbExists(): bool
    {
        $stmt = $this->pdo->query("SELECT 1 FROM pg_database WHERE datname = '$this->dbname'");
        return $stmt->fetchColumn() == 1;
    }

    public function createTables(): void
    {
        dump(__DIR__);
        $sql = file_get_contents(__DIR__ . '/schema.sql');
        $this->pdo->exec($sql);
    }

    public function seedData(): void
    {
        $sql = file_get_contents(__DIR__ . '/seed.sql');
        $this->pdo->exec($sql);
    }
}
<?php

namespace PhpWeb;

class Database
{
    // TODO подставить значения из .env
    private string $host = 'localhost';
    private string $username = 'postgres'; // по умолчанию
    private string $password = 'your_password'; // по умолчанию
    private string $dbname = 'myapp';
    private \PDO $pdo;

    public function __construct()
    {
        $this->connect();
    }

    private function connect(): void
    {
        try {
            $this->pdo = new \PDO("pgsql:host=$this->host;dbname=postgres", $this->username, $this->password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("❌ Cannot connect to PostgreSQL: " . $e->getMessage());
        }
    }

    public function createDatabase(): void
    {
        if (!$this->dbExists()) {
            echo "Creating database: $this->dbname\n";
            $stmt = $this->pdo->prepare("CREATE DATABASE $this->dbname");
            $stmt->execute();
            $this->pdo = null;
            $this->connect(); // переподключаемся к новой БД
        }
    }

    private function dbExists(): bool
    {
        $stmt = $this->pdo->query("SELECT 1 FROM pg_database WHERE datname = '$this->dbname'");
        return $stmt->fetch() !== false;
    }

    public function createTables(): void
    {
        $sql = file_get_contents(__DIR__ . '/database/schema.sql');
        $this->pdo->exec($sql);
    }

    public function seedData(): void
    {
        $sql = file_get_contents(__DIR__ . '/database/seed.sql');
        $this->pdo->exec($sql);
    }
}
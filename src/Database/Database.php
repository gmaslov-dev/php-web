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
        $this->host = $_ENV['DB_HOST'] ?? 'postgres';
        $this->username = $_ENV['DB_USERNAME'] ?? 'postgres';
        $this->password = $_ENV['DB_PASSWORD'] ?? 'postgres';
        $this->dbname = $_ENV['DB_NAME'] ?? 'myapp';

        // Подключаемся к системной БД
        $this->connectToSystemDb();
    }

    private function connectToSystemDb(): void
    {
        try {
            $dsn = "pgsql:host=localhost;port=5432;dbname=postgres";
            $this->pdo = new \PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            echo "✅ Connected to system DB\n";
        } catch (\PDOException $e) {
            echo "❌ Cannot connect to system DB: " . $e->getMessage() . "\n";
        }
    }

    private function connect(): void
    {
        try {
            $dsn = "pgsql:host=localhost;port=5432;dbname=$this->dbname";
            $this->pdo = new \PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            echo "✅ Connected to main DB\n";
        } catch (\PDOException $e) {
            echo "❌ Cannot connect to main DB: " . $e->getMessage() . "\n";
        }
    }

    public function createDatabase(): void
    {
        if ($this->pdo === null) {
            echo "⚠️ Cannot create database without connection.\n";
            return;
        }

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
        if ($this->pdo === null) {
            echo "⚠️ Cannot check DB existence without connection.\n";
            return false;
        }

        $stmt = $this->pdo->query("SELECT 1 FROM pg_database WHERE datname = '$this->dbname'");
        return $stmt->fetchColumn() === '1';
    }

    public function createTables(): void
    {
        if ($this->pdo === null) {
            $this->connect();
        }

        $sql = file_get_contents(__DIR__ . '/schema.sql');
        $this->pdo->exec($sql);
        echo "✅ Tables created.\n";
    }

    public function seedData(): void
    {
        if ($this->pdo === null) {
            $this->connect();
        }

        $sql = file_get_contents(__DIR__ . '/seed.sql');
        $this->pdo->exec($sql);
        echo "✅ Data seeded.\n";
    }
}
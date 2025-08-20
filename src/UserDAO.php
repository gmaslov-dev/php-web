<?php

namespace PhpWeb;

use PDO;

class UserDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function save(User $user): void
    {
        $sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':name' => $user->getName(),
            ':email' => $user->getEmail()
        ]);
        $id = $this->pdo->lastInsertId();
        $user->setId($id);
    }

    public function find(int $id): ?User
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        if ($result) {
            $user = new User($result['name'], $result['email']);
            $user->setId($result['id']);
            return $user;
        }
        return null;
    }

    public function findByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch();
        if ($result) {
            $user = new User($result['email'], $result['name']);
            $user->setId($result['id']);
            return $user;
        }
        return null;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM users');
        $rows = $stmt->fetchAll();

        return array_map(fn($row) => new User($row['email'], $row['name']), $rows);
    }
}
<?php

namespace PhpWeb\Repository;

use PDO;
use PhpWeb\Entity\User;

class UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(User $user): void
    {
        $sql = "INSERT INTO users (name, email) VALUES (:name, :email) RETURNING id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':name' => $user->getName(),
            ':email' => $user->getEmail()
        ]);
        $id = $stmt->fetchColumn();
        $user->setId($id);
    }

    public function update(User $user): void
    {
        $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':name' => $user->getName(),
            ':email' => $user->getEmail(),
            ':id' => $user->getId()
        ]);
    }

    public function find(int $id): ?User
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

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
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $user = new User($result['name'], $result['email']);
            $user->setId($result['id']);
            return $user;
        }

        return null;
    }

    private function createUser(array $row): User
    {
        $user = new User($row['name'], $row['email']);
        $user->setId($row['id']);
        return $user;
    }

    public function get($limit, $offset): array
    {
        $sql = "SELECT * FROM users LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':limit' => $limit, ':offset' => $offset]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => $this->createUser($row), $rows);
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => $this->createUser($row), $rows);
    }
}
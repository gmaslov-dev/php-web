<?php

namespace PhpWeb;

interface DAO
{
    public function save(object $entity): void;
    public function update(object $entity): void;
    public function find(int $id): ?object;
    public function findByEmail(string $email): ?object;
    public function findAll(): array;
}
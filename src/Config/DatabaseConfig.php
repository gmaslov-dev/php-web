<?php

namespace PhpWeb\Config;

class DatabaseConfig
{
    public function getDsn(): string
    {
        return "pgsql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}";
    }

    public function getUsername(): string
    {
        return $_ENV['DB_USER'];
    }

    public function getPassword(): string
    {
        return $_ENV['DB_PASSWORD'];
    }
}
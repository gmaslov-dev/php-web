<?php

use DI\Container;
use PhpWeb\Repository\UserRepository;
use PhpWeb\Twig\AppExtension;
use Slim\Flash\Messages;
use Slim\Views\Twig;

$container = new Container();

$container->set(PDO::class, function() {
    $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
    $username = $_ENV['DB_USERNAME'] ?? 'postgres';
    $password = $_ENV['DB_PASSWORD'] ?? 'postgres';
    $dbname = $_ENV['DB_NAME'] ?? 'postgres';

    $dsn = "pgsql:host=$host;port=5432;dbname=$dbname";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
});

$container->set(UserRepository::class, fn($c) => new UserRepository($c->get(PDO::class)));

$container->set(Twig::class, function () {
    $twig = Twig::create(__DIR__ . '/../templates', ['cache' => false, 'debug' => true]);
    $twig->addExtension(new AppExtension());
    return $twig;
});

$container->set('flash', fn() => new Messages());

return $container;

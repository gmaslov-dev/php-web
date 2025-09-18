<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigTest extends TestCase
{
    protected $twig;

    protected function setUp(): void
    {
        // Путь к папке с шаблонами
        $loader = new FilesystemLoader(__DIR__ . '/../templates');
        $this->twig = new Environment($loader);
    }

    public function testRenderNewUserForm()
    {
        $userData = ['name' => 'Иван', 'email' => 'ivan@example.com'];
        $errors = [];
        $success = ['Пользователь успешно сохранён'];

        $output = $this->twig->render('users/new.twig', [
            'userData' => $userData,
            'errors' => $errors,
            'success' => $success,
        ]);

        // Проверяем, что форма содержит текст "Сохранить"
        $this->assertStringContainsString('Сохранить', $output);

        // Проверяем, что имя пользователя отображается
        $this->assertStringContainsString('value="Иван"', $output);

        // Проверяем, что email отображается
        $this->assertStringContainsString('value="ivan@example.com"', $output);

        // Проверяем, что сообщение об успехе отображается
        $this->assertStringContainsString('Пользователь успешно сохранён', $output);
    }
}
<?php

use PhpWeb\User;
use PhpWeb\UserDAO;
use PhpWeb\Validator;
use Slim\Factory\AppFactory;
use DI\Container;
use Slim\Views\PhpRenderer;

require __DIR__ . '/../vendor/autoload.php';

$conn = new PDO('sqlite:php-web.sqlite');
$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$conn->exec(file_get_contents('init.sql'));


$dao = new UserDAO($conn);
$user = new User('abc@mail.ex', 'User');
$dao->save($user);


$container = new Container();
$container->set('renderer', function () {
    return new PhpRenderer(__DIR__ . '/../templates');
});

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

// стартовая страница
$app->get('/', function ($request, $response) use ($user) {
    $renderer = $this->get('renderer');
    $content = $renderer->fetch('index.phtml', ['id' => $user->getId()]);
    return $this->get('renderer')->render($response, "layout.phtml", ['content' => $content]);
});

$app->get('/users/new', function ($request, $response) {
    return $this->get('renderer')->render($response, 'users/new.phtml');
});

// пользователи


$app->run();
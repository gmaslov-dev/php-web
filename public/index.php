<?php

use PhpWeb\User;
use PhpWeb\UserDAO;
use PhpWeb\UserValidator;
use Slim\Factory\AppFactory;
use DI\Container;
use Slim\Views\PhpRenderer;

require __DIR__ . '/../vendor/autoload.php';

$conn = new PDO('sqlite:../php-web.sqlite');
$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$dao = new UserDAO($conn);

$container = new Container();
$container->set('renderer', function () {
    return new PhpRenderer(__DIR__ . '/../templates');
});

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addRoutingMiddleware();     // Сначала роутинг
$app->addBodyParsingMiddleware(); // Потом парсинг тела
$app->addErrorMiddleware(true, true, true); // И ошибки в конце

// стартовая страница
$app->get('/', function ($request, $response) {
    $renderer = $this->get('renderer')->fetch('index.phtml');
    $params = ['title' => 'Homepage', 'content' => $renderer];
    return $this->get('renderer')->render($response, "layout.phtml", $params);
});

// пользователи
$app->post('/users', function ($request, $response) use ($dao) {
    $validator = new UserValidator();
    $user = $request->getParsedBodyParam('user');
    $errors = $validator->validate($user, $dao);

    if (count($errors) === 0) {
        $user = new User($user['nickname'], $user['email']);
        $dao->save($user);
        return $response->withRedirect('/users', 302);
    }
    return $response->withRedirect('/users');
});

$app->get('/users', function ($request, $response) use ($dao) {
    $users = $dao->getAll();
    $renderer = $this->get('renderer')->fetch('users/index.phtml', ['users' => $users]);
    $params = ['content' => $renderer];
    return $this->get('renderer')->render($response, "layout.phtml", $params);
});

$app->get('/users/new', function ($request, $response) {
    $renderer = $this->get('renderer')->fetch('users/new.phtml');
    return $this->get('renderer')->render($response, "layout.phtml", ['content' => $renderer]);
});

$app->run();
<?php

        // Подключение автозагрузки через composer
use Slim\Factory\AppFactory;
use DI\Container;
use User\PhpWeb\Generator;

require __DIR__ . '/../vendor/autoload.php';

// Список пользователей
// Каждый пользователь – ассоциативный массив
// следующей структуры: id, firstName, lastName, email
$users = Generator::generate(100);

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, 'index.phtml');
});




$app->get('/users', function ($request, $response) use ($users) {
    return $this->get('renderer')->render($response, 'users/index.phtml', ['users' => $users]);
});

$app->get('/users/{id}', function ($request, $response, $args) use ($users) {
    if (isset($users[$args['id']])) {
        return $this->get('renderer')->render($response, 'users/show.phtml', ['user' => $users[$args['id']]]);
    } else {
        return $response->withStatus(404)->write('User not found');
    }
});
// END

$app->run();
<?php

        // Подключение автозагрузки через composer
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    return $response->getBody()->write('Welcome to Slim!');
    // Благодаря пакету slim/http этот же код можно записать короче
    // return $response->write('Welcome to Slim!');
});

$app->get('/users', function ($request, $response) {
    return $response->write('GET /users');
});

$app->post('/users', function ($request, $response) {
    return $response->write('POST /users');
});


// $app->post('/users', function ($request, $response) {
//     $data = ['message' => 'POST /users'];
//     return $response->withJson($data); // Автоматически добавляет Content-Type и возвращает JSON
// });

$app->run();
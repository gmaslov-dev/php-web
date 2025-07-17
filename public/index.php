<?php

        // Подключение автозагрузки через composer
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$companies = User\PhpWeb\Generator::generate(100);

$app->get('/', function ($request, $response) {
    return $response->write('go to the /companies');
});

// BEGIN (write your solution here)
$app->get('/companies', function($request, $response) use ($companies) {
    $page = $request->getQueryParam('page', 1);
    $per = $request->getQueryParam('per', 5);

    $result = array_slice($companies, ($page - 1) * $per, $per);
    return $response->withJson($result);
});
// END

$app->run();
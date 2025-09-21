<?php

use Slim\App;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    $container = $app->getContainer();

    $app->addErrorMiddleware(true, true, true);
    $app->add(MethodOverrideMiddleware::class);
    $app->add(TwigMiddleware::create($app, $container->get(Twig::class)));
};
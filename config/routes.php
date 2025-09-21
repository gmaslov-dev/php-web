<?php

use PhpWeb\Controller\PagesController;
use PhpWeb\Controller\UserController;
use PhpWeb\Middleware\LogMiddleware;
use Slim\App;

return function (App $app) {
    $app->get('/', PagesController::class)->setName('home')->add(new LogMiddleware());

    $app->get('/users', [UserController::class, 'index'])->setName('users');
    $app->get('/users/new', [UserController::class, 'new'])->setName('users.new');
};
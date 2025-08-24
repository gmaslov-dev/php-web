<?php

use PhpWeb\User;
use PhpWeb\UserDAO;
use PhpWeb\UserValidator;
use Slim\Factory\AppFactory;
use DI\Container;
use Slim\Routing\RouteContext;
use Slim\Views\PhpRenderer;

require __DIR__ . '/../vendor/autoload.php';

session_start();

$conn = new PDO('sqlite:../php-web.sqlite');
$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$container = new Container();
// Регистрируем DAO
$container->set(UserDAO::class, function () use ($conn) {
    return new UserDAO($conn);
});

// Регистрируем Renderer
$container->set('renderer', function () {
    return new PhpRenderer(__DIR__ . '/../templates');
});

// Регистрируем Flash
$container->set('flash', function () {
    return new \Slim\Flash\Messages();
});

AppFactory::setContainer($container);
$app = AppFactory::create();

// Мидлвары
$app->addRoutingMiddleware();     // Сначала роутинг
$app->addBodyParsingMiddleware(); // Потом парсинг тела
$app->addErrorMiddleware(true, true, true); // И ошибки в конце

// Маршруты
$app->get('/', function ($request, $response) use ($app) {
    $renderer = $app->getContainer()->get(PhpRenderer::class);


    $params = [
        'title' => 'Homepage',
        'header' => $renderer->fetch('../templates/nav.phtml'),
        'router' => $router
    ];
    return $renderer->render($response, "index.phtml", $params);
})->setName('home');


//// пользователи
//$app->get('/users', function ($request, $response) {
//    $dao = $this->get(UserDAO::class);
//    $renderer = $this->get(PhpRenderer::class);
//    $router = $this->getRouter()->getRouteParser();
//
//    $users = $dao->getAll();
//    return $this->get('renderer')->render($response, "users/index.phtml", ['users' => $users, 'router' => $router, 'renderer' => $renderer]);
//})->setName('users.get');
//
//$app->post('/users', function ($request, $response) {
//    $validator = new UserValidator();
//    $user = $request->getParsedBodyParam('user');
//    $errors = $validator->validate($user, $dao);
//    $this->get('flash')->addMessage('success', 'User was added succesfully');
//
//    if (count($errors) === 0) {
//        $user = new User($user['nickname'], $user['email']);
//        $dao->save($user);
//        return $response->withRedirect('/users', 302);
//    }
//    return $response->withRedirect('/users');
//})->setName('users.post');
//
//
//
//$app->get('/users/new', function ($request, $response)  {
//    $renderer = $this->get('renderer')->fetch('users/new.phtml');
//    return $this->get('renderer')->render($response, "layout.phtml", ['content' => $renderer, 'router' => $router]);
//})->setName('users/new');
//
//$app->get('/users/{id}', function ($request, $response) {
//    $userId = $request->getAttribute('id');
//
//
//
//    return $this->get('renderer')->render($response, "layout.phtml");
//});
$app->run();
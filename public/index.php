<?php

use PhpWeb\User;
use PhpWeb\UserDAO;
use PhpWeb\UserValidator;
use Slim\Factory\AppFactory;
use DI\Container;
use Slim\Views\PhpRenderer;
// use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

//$dotenv = Dotenv::createImmutable(__DIR__);
//$dotenv->load();

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

$router = $app->getRouteCollector()->getRouteParser();

// стартовая страница
$app->get('/', function ($request, $response) use ($router){

    $renderer = $this->get('renderer')->fetch('index.phtml');

    $params = [
        'title' => 'Homepage',
        'content' => $renderer,
        'router' => $router
    ];
    return $this->get('renderer')->render($response, "layout.phtml", $params);
})->setName('home');

// пользователи
$app->post('/users', function ($request, $response) use ($dao, $app, $router) {
    $validator = new UserValidator();
    $user = $request->getParsedBodyParam('user');
    $errors = $validator->validate($user, $dao);

    if (count($errors) === 0) {
        $user = new User($user['nickname'], $user['email']);
        $dao->save($user);
        return $response->withRedirect('/users', 302);
    }
    return $response->withRedirect('/users', ['router' => $router]);
})->setName('users.post');

$app->get('/users', function ($request, $response) use ($dao, $app, $router) {
    $users = $dao->getAll();
    $renderer = $this->get('renderer')->fetch('users/index.phtml', ['users' => $users]);
    $params = ['content' => $renderer, 'router' => $router];

    return $this->get('renderer')->render($response, "layout.phtml", $params);
})->setName('users.get');

$app->get('/users/new', function ($request, $response) use ($router) {
    $renderer = $this->get('renderer')->fetch('users/new.phtml', ['router' => $router]);
    return $this->get('renderer')->render($response, "layout.phtml", ['content' => $renderer, 'router' => $router]);
})->setName('users/new');

$app->get('/users/{id}', function ($request, $response) use ($dao, $router) {
    $userId = $request->getAttribute('id');
    $user = $dao->find($userId);

    if (!$user) {
        return $response->withStatus(404)->write('Пользователь не найден');
    }

    $renderer = $this->get('renderer')->fetch('users/show.phtml', ['name' => $user->getName(), 'email' => $user->getEmail()]);

    return $this->get('renderer')->render($response, "layout.phtml", ['content' => $renderer, 'router' => $router]);
});
$app->run();
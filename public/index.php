<?php

use PhpWeb\User;
use PhpWeb\UserDAO;
use PhpWeb\UserValidator;
use Slim\Factory\AppFactory;
use DI\Container;
use Slim\Flash\Messages;
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
    return new Messages();
});

$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

// Маршруты
$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, 'index.phtml');
})->setName('home');


// Пользователи
$app->get('/users', function ($request, $response) {
    $limit = 5;
    $page = (int) $request->getQueryParam('page', 1);
    $currentPage = max(1, $page);

    $users = $this->get(UserDAO::class)->getAll();
    $totalPages = ceil(count($users) / $limit);
    $currentUsers = array_slice($users, (($page - 1) * $limit), $limit);

    $params = [
        'users' => $currentUsers,
        'page' => $currentPage,
        'pages' => $totalPages
    ];

    return $this->get('renderer')->render($response, "users/index.phtml", $params);
})->setName('users');



// Пользователь
$app->get('/users/{id}', function ($request, $response, array $args) {
    $id = $args['id'];
    $UserDAO = $this->get(UserDAO::class);
    $user = $UserDAO->find($id);

    if (!$user) {
       return $response->write('Page not found')->withStatus(404);
    }

    $params = ['user' => $user];

    return $this->get('renderer')->render($response, 'users/show.phtml', $params);
})->setName('user');

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
<?php

use PhpWeb\Controller\UserController;
use PhpWeb\UserDAO;
use Slim\Factory\AppFactory;
use DI\Container;
use Slim\Flash\Messages;
use Slim\Views\PhpRenderer;
use Slim\Middleware\MethodOverrideMiddleware;

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
$container->set(PhpRenderer::class, function () {
    return new PhpRenderer(__DIR__ . '/../templates');
});

// Регистрируем Flash
$container->set('flash', function () {
    return new Messages();
});

$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);
$app->add(MethodOverrideMiddleware::class);

// Регистрируем роутер
$router = $app->getRouteCollector()->getRouteParser();
$container->set('router', $router);

$app->get('/users', [UserController::class, 'index'])->setName('users');


// Маршруты
//$app->get('/', function ($request, $response) {
//    return $this->get('renderer')->render($response, 'index.phtml');
//})->setName('home');
//
//// Все пользователи
//$app->get('/users', function ($request, $response) {
//    $limit = 5;
//    $page = (int) $request->getQueryParam('page', 1);
//    $currentPage = max(1, $page);
//
//    $users = $this->get(UserDAO::class)->getAll();
//    $totalPages = ceil(count($users) / $limit);
//    $currentUsers = array_slice($users, (($page - 1) * $limit), $limit);
//
//    $params = [
//        'users' => $currentUsers,
//        'page' => $currentPage,
//        'pages' => $totalPages
//    ];
//
//    return $this->get('renderer')->render($response, "users/index.phtml", $params);
//})->setName('users');
//
//// Создание пользователя
//$app->get('/users/new', function ($request, $response) {
//    $routeContext = RouteContext::fromRequest($request);
//    $router = $routeContext->getRouteParser();
//    $success = $this->get('flash')->getMessages()['success'] ?? [];
//
//    $params = [
//        'userData' => [],
//        'errors' => [],
//        'router' => $router,
//        'success' => $success
//    ];
//
//    return $this->get('renderer')->render($response, 'users/new.phtml', $params);
//})->setName('users.new');
//
//// Сохранение пользователя
//$app->post('/users', function ($request, $response) {
//    $dao = $this->get(UserDAO::class);
//    $userData = $request->getParsedBodyParam('user');
//    $validator = new UserValidator();
//
//    $errors = $validator->validateEmpty($userData, $dao);
//    $errors = $validator->validateUniqEmail($userData['email'], $dao);
//    if (count($errors) === 0) {
//        $user = new User($userData['name'], $userData['email']);
//        $dao->save($user);
//        $this->get('flash')->addMessage('success', 'User was added succesfully');
//        return $response->withRedirect($this->get('router')->urlFor('users.new'), 302);
//    }
//
//    $params = [
//        'schoolData' => $userData,
//        'errors' => $errors
//    ];
//
//    return $this->get('renderer')->render($response, 'users/new.phtml', $params)->withStatus(422);
//})->setName('users.post');
//
//$app->patch('/users/{id}', function ($request, $response, array $args) {
//    $UserDAO = $this->get(UserDAO::class);
//    $id = $args['id'];
//    $user = $UserDAO->find($id);
//    $data = $request->getParsedBodyParam('user');
//
//    $validator = new UserValidator();
//    $errors = $validator->validateEmpty(['name' => $user->getName(), 'email' => $user->getEmail()], $UserDAO);
//    if (count($errors) === 0) {
//        $user->setName($data['name']);
//        $user->setEmail($data['email']);
//        $this->get('flash')->addMessage('success', 'User was updated succesfully');
//        $UserDAO->update($user);
//        $url = $this->get('router')->urlFor('user', ['id' => $id]);
//        return $response->withRedirect($url);
//    }
//
//    $params = [
//        'userData' => ['id' => $id, ...$data],
//        'errors' => $errors,
//    ];
//
//    return $this->get('renderer')->render($response, 'users/edit.phtml', $params)->withStatus(422);
//});
//
//// Конкретный пользователь
//$app->get('/users/{id}', function ($request, $response, array $args) {
//
//    $UserDAO = $this->get(UserDAO::class);
//    $id = $args['id'];
//    $user = $UserDAO->find($id);
//
//    if (!$user) {
//       return $response->write('Page not found')->withStatus(404);
//    }
//    $success = $this->get('flash')->getMessages()['success'] ?? [];
//    $params = ['user' => $user, 'success' => $success];
//
//    return $this->get('renderer')->render($response, 'users/show.phtml', $params);
//})->setName('user');
//
//// Обновление пользователя
//$app->get('/users/{id}/edit', function ($request, $response, array $args) {
//    $id = $args['id'];
//    $user = $this->get(UserDAO::class)->find($id);
//    dump($user);
//    $params = [
//        'userData' => [
//            'id' => $user->getId(),
//            'name' => $user->getName(),
//            'email' => $user->getEmail()
//        ],
//        'errors' => []
//    ];
//    return $this->get('renderer')->render($response, 'users/edit.phtml', $params);
//})->setName('user.edit');

$app->run();
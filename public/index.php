<?php

use DI\Container;
use PhpWeb\Controller\UserController;
use PhpWeb\Repository\UserRepository;
use PhpWeb\Twig\AppExtension;
use Slim\Factory\AppFactory;
use Slim\Flash\Messages;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$container = new Container();

// Регистрация PDO
$container->set(PDO::class, function () {
    $host = $_ENV['DB_HOST'] ?? 'postgres';
    $username = $_ENV['DB_USERNAME'] ?? 'postgres';
    $password = $_ENV['DB_PASSWORD'] ?? 'postgres';
    $dbname = $_ENV['DB_NAME'] ?? 'myapp';

    $dsn = "pgsql:host=$host;port=5432;dbname=$dbname";
    $pdo = new \PDO($dsn, $username, $password);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    return $pdo;
});

// Регистрируем Repository
$container->set(UserRepository::class, function ($c) {
    return new UserRepository($c->get(PDO::class));
});

// Регистрируем Twig
$container->set(Twig::class, function() {
    $twig = Twig::create(__DIR__ . '/../templates', ['cache' => false]);
    $twig->addExtension(new AppExtension());

    return $twig;
});

// Регистрируем Flash
$container->set('flash', function () {
    return new Messages();
});

$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);
$app->add(MethodOverrideMiddleware::class);

$app->add(TwigMiddleware::create($app, $container->get(Twig::class)));

$app->get('/users', [UserController::class, 'index'])->setName('users');
$app->get('/users/new', [UserController::class, 'new'])->setName('users.new');


// Маршруты
//$app->get('/', function ($request, $response) {
//    return $this->get('renderer')->render($response, 'index.twig');
//})->setName('home');
//
//// Все пользователи
//$app->get('/users', function ($request, $response) {
//    $limit = 5;
//    $page = (int) $request->getQueryParam('page', 1);
//    $currentPage = max(1, $page);
//
//    $users = $this->get(UserRepository::class)->getAll();
//    $totalPages = ceil(count($users) / $limit);
//    $currentUsers = array_slice($users, (($page - 1) * $limit), $limit);
//
//    $params = [
//        'users' => $currentUsers,
//        'page' => $currentPage,
//        'pages' => $totalPages
//    ];
//
//    return $this->get('renderer')->render($response, "users/index.twig", $params);
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
//    return $this->get('renderer')->render($response, 'users/new.twig', $params);
//})->setName('users.new');
//
//// Сохранение пользователя
//$app->post('/users', function ($request, $response) {
//    $dao = $this->get(UserRepository::class);
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
//    return $this->get('renderer')->render($response, 'users/new.twig', $params)->withStatus(422);
//})->setName('users.post');
//
//$app->patch('/users/{id}', function ($request, $response, array $args) {
//    $UserRepository = $this->get(UserRepository::class);
//    $id = $args['id'];
//    $user = $UserRepository->find($id);
//    $data = $request->getParsedBodyParam('user');
//
//    $validator = new UserValidator();
//    $errors = $validator->validateEmpty(['name' => $user->getName(), 'email' => $user->getEmail()], $UserRepository);
//    if (count($errors) === 0) {
//        $user->setName($data['name']);
//        $user->setEmail($data['email']);
//        $this->get('flash')->addMessage('success', 'User was updated succesfully');
//        $UserRepository->update($user);
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
//    $UserRepository = $this->get(UserRepository::class);
//    $id = $args['id'];
//    $user = $UserRepository->find($id);
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
//    $user = $this->get(UserRepository::class)->find($id);
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
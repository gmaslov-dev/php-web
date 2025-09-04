<?php

use PhpWeb\Controller\UserController;
use PhpWeb\UserRepository;
use Slim\Factory\AppFactory;
use DI\Container;
use Slim\Flash\Messages;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\Twig;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

require __DIR__ . '/../vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host     = $_ENV['DB_HOST'];
$dbname   = $_ENV['DB_NAME'];
$user     = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    error_log("Подключение к PostgreSQL установлено!");
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

$container = new Container();

// Регистрируем Repository
$container->set(UserRepository::class, function () use ($conn) {
    return new UserRepository($conn);
});

// Регистрируем Twig
$container->set(Twig::class, function () {
    $loader = new FilesystemLoader(__DIR__ . '/../templates');
    $twig = new Twig($loader, [
        'cache' => false,
        'debug' => true,
    ]);

    $twig->addExtension(new DebugExtension());

    return $twig;
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
//    return $this->get('renderer')->render($response, 'users/new.phtml', $params);
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
//    return $this->get('renderer')->render($response, 'users/new.phtml', $params)->withStatus(422);
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
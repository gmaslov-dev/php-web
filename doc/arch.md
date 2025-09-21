Обычно структура выглядит так:

```
project/
├── config/
│   ├── container.php    # настройка DI-контейнера
│   ├── middleware.php   # регистрация middleware
│   └── routes.php       # маршруты
├── public/
│   └── index.php        # точка входа, здесь только bootstrap + run()
├── src/
│   ├── Controller/      # контроллеры
│   ├── Repository/      # репозитории
│   ├── Middleware/      # кастомные middleware
│   └── Twig/            # расширения для Twig
├── bootstrap/
│   └── app.php          # сборка Slim-приложения
├── templates/           # twig-шаблоны
├── .env
└── composer.json
```

### Пример разделения

**`public/index.php`** (только запуск):

```php
<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

session_start();

$app = require __DIR__ . '/../bootstrap/app.php';

$app->run();
```

**`bootstrap/app.php`** (инициализация приложения):

```php
<?php

use Slim\Factory\AppFactory;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$container = require __DIR__ . '/../config/container.php';

AppFactory::setContainer($container);
$app = AppFactory::create();

(require __DIR__ . '/../config/middleware.php')($app);
(require __DIR__ . '/../config/routes.php')($app);

return $app;
```

**`config/container.php`** (DI-контейнер):

```php
<?php

use DI\Container;
use PhpWeb\Repository\UserRepository;
use PhpWeb\Twig\AppExtension;
use Slim\Flash\Messages;
use Slim\Views\Twig;

$container = new Container();

$container->set(PDO::class, function () {
    $host = $_ENV['DB_HOST'] ?? 'postgres';
    $username = $_ENV['DB_USERNAME'] ?? 'postgres';
    $password = $_ENV['DB_PASSWORD'] ?? 'postgres';
    $dbname = $_ENV['DB_NAME'] ?? 'myapp';

    $dsn = "pgsql:host=$host;port=5432;dbname=$dbname";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
});

$container->set(UserRepository::class, fn($c) => new UserRepository($c->get(PDO::class)));

$container->set(Twig::class, function () {
    $twig = Twig::create(__DIR__ . '/../templates', ['cache' => false]);
    $twig->addExtension(new AppExtension());
    return $twig;
});

$container->set('flash', fn() => new Messages());

return $container;
```

**`config/middleware.php`**:

```php
<?php

use Slim\App;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    $container = $app->getContainer();

    $app->addErrorMiddleware(true, true, true);
    $app->add(MethodOverrideMiddleware::class);
    $app->add(TwigMiddleware::create($app, $container->get(Twig::class)));
};
```

**`config/routes.php`**:

```php
<?php

use Slim\App;
use PhpWeb\Controller\UserController;

return function (App $app) {
    $app->get('/users', [UserController::class, 'index'])->setName('users');
    $app->get('/users/new', [UserController::class, 'new'])->setName('users.new');
};
```

---

✅ В итоге:

* `public/index.php` → только точка входа.
* `bootstrap/app.php` → собирает всё приложение.
* `config/*` → настройки контейнера, middleware, маршрутов.
* `src/*` → бизнес-логика (контроллеры, репозитории, сервисы).

Так архитектурно правильнее и легче поддерживать.

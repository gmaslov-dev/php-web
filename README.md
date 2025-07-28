Вот готовый `README.md` на русском языке для вашего репозитория GitHub:

---

```markdown
# 🐳 PHP 8.4 + MySQL + PostgreSQL + phpMyAdmin + pgAdmin + Xdebug

Полноценная среда разработки на Docker Compose с PHP 8.4, Composer, двумя СУБД (MySQL и PostgreSQL), phpMyAdmin, pgAdmin и Xdebug.

---

## ⚙️ Состав

- **PHP 8.4 FPM** с Composer и Xdebug
- **MySQL 8.0**
- **PostgreSQL 15**
- **phpMyAdmin** — управление MySQL через веб
- **pgAdmin** — управление PostgreSQL через веб
- **Nginx** — веб-сервер
- **Поддержка PDO (MySQL, PostgreSQL, SQLite)**

---

## 📁 Структура проекта

```

project/
├── docker/
│   ├── php/
│   │   └── local.ini
│   └── nginx/
│       └── default.conf
├── public/
│   └── index.php
├── .env
├── Dockerfile
├── docker-compose.yml
└── README.md

````

---

## 🚀 Запуск

```bash
docker-compose up -d
````

## 🎼 Composer команды

```bash
docker-compose run --rm composer install
docker-compose run --rm composer require package/name
```

---

## 🌐 Доступ к сервисам

| Сервис     | URL                                            |
| ---------- | ---------------------------------------------- |
| Приложение | [http://localhost:8000](http://localhost:8000) |
| phpMyAdmin | [http://localhost:8080](http://localhost:8080) |
| pgAdmin    | [http://localhost:8081](http://localhost:8081) |
| MySQL      | localhost:3306                                 |
| PostgreSQL | localhost:5432                                 |

---

## 🧠 Настройка Xdebug

В файле `docker/php/local.ini` уже настроено следующее:

```ini
xdebug.mode=develop,debug
xdebug.start_with_request=yes
xdebug.client_host=host.docker.internal
xdebug.client_port=9003
```

В IDE (например, PhpStorm):

* Хост: `localhost`
* Порт: `9003`
* IDE Key: `PHPSTORM`

---

## 🔐 .env файл (пример)

```env
# MySQL
MYSQL_DATABASE=laravel_app
MYSQL_USERNAME=laravel_user
MYSQL_PASSWORD=secure_password_123
MYSQL_ROOT_PASSWORD=root_password_456

# PostgreSQL
POSTGRES_DATABASE=postgres_app
POSTGRES_USERNAME=postgres_user
POSTGRES_PASSWORD=postgres_password_789

# pgAdmin
PGADMIN_DEFAULT_EMAIL=admin@example.com
PGADMIN_DEFAULT_PASSWORD=admin_password_321

# Laravel/Приложение
APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# DB по умолчанию
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=${MYSQL_DATABASE}
DB_USERNAME=${MYSQL_USERNAME}
DB_PASSWORD=${MYSQL_PASSWORD}

# Альтернативное подключение к PostgreSQL
POSTGRES_HOST=postgres
POSTGRES_PORT=5432
```

---

## 📦 Подключение к базам из PHP

**MySQL:**

```php
$pdo = new PDO('mysql:host=mysql;dbname=laravel_app', 'laravel_user', 'secure_password_123');
```

**PostgreSQL:**

```php
$pdo = new PDO('pgsql:host=postgres;dbname=postgres_app', 'postgres_user', 'postgres_password_789');
```

**SQLite (опционально):**

```php
$pdo = new PDO('sqlite:/path/to/database.db');
```

---

## 📂 Файлы JavaScript, CSS и картинки

Помещайте все статические файлы в папку `public/`, например:

```
public/
├── js/
│   └── app.js
├── css/
│   └── style.css
├── images/
│   └── logo.png
└── index.php
```

Подключение в HTML:

```html
<script src="/js/app.js"></script>
<link rel="stylesheet" href="/css/style.css">
```

---

## 🧱 Используемые расширения PHP

* pdo, pdo\_mysql, pdo\_pgsql, pdo\_sqlite
* mbstring, bcmath, zip, gd, exif, pcntl, xdebug

---

## 📦 Возможности

✅ Совместная работа с MySQL и PostgreSQL
✅ Поддержка Xdebug 3
✅ Изоляция конфигурации через `.env`
✅ Удобный веб-интерфейс phpMyAdmin и pgAdmin
✅ Простое расширение под Laravel, Symfony, WordPress и др.

---

## 📜 Лицензия

MIT

```

---

Если хочешь, могу сразу сгенерировать и все нужные `.yml`, `Dockerfile` и `.ini` файлы в одном архиве.
```


## ✅ шаблон на чистом Twig:

```twig
{# templates/users/index.twig #}

<table>
    {% for user in users %}
        <tr>
            <td>
                <a href="{{ url_for('user', {'id': user.id}) }}">{{ user.name }}</a>
            </td>
            <td>
                {{ user.email }}
            </td>
        </tr>
    {% endfor %}
</table>

<nav>
    {% if page > 1 %}
        <a href="/users?page={{ page - 1 }}">назад</a>
    {% endif %}

    {% if page < pages %}
        <a href="/users?page={{ page + 1 }}">вперед</a>
    {% endif %}
</nav>
```

---

## 🛠 Объяснение изменений:

| PHP | Twig |
|-----|------|
| `<?php if($page > 1): ?>` | `{% if page > 1 %}` |
| `<?= $page - 1?>` | `{{ page - 1 }}` |
| `<?php endif; ?>` | `{% endif %}` |

---

## 🔗 Ссылка на пользователя (`href="{{ user.id }}"`)

`href="{{ user.id }}"`, но это сгенерирует ссылку вроде `/1`, `/2`, `/3`.

Если есть маршрут для отображения одного пользователя, например:

```php
$app->get('/users/{id}', [UserController::class, 'show'])->setName('user');
```

Тогда правильнее использовать в шаблоне:

```twig
<a href="{{ url_for('user', {'id': user.id}) }}">{{ user.name }}</a>
```

---

## ✅ Дополнительно: использование `url_for` в Twig

Чтобы использовать `url_for` внутри шаблонов, нужно зарегистрировать `RouteContext` как сервис или передать его в шаблон через контроллер.

Пример:

### В контроллере:

```php
public function index(Request $request, Response $response): Response
{
    // ... другие параметры
    $params['router'] = RouteContext::fromRequest($request)->getRouteParser();

    return $this->view->render($response, 'users/index.twig', $params);
}
```

### В шаблоне:

```twig
<a href="{{ router.urlFor('user', {'id': user.id}) }}">{{ user.name }}</a>
```

---

## ✅ Вывод

Обновлённый шаблон `index.twig`, полностью на Twig:

```twig
<table>
    {% for user in users %}
        <tr>
            <td>
                <a href="{{ router.urlFor('user', {'id': user.id}) }}">{{ user.name }}</a>
            </td>
            <td>
                {{ user.email }}
            </td>
        </tr>
    {% endfor %}
</table>

<nav>
    {% if page > 1 %}
        <a href="/users?page={{ page - 1 }}">назад</a>
    {% endif %}

    {% if page < pages %}
        <a href="/users?page={{ page + 1 }}">вперед</a>
    {% endif %}
</nav>
```

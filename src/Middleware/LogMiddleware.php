<?php

namespace PhpWeb\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

// выводит сообщение в лог и в тело ответа
class LogMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // 1. Логируем запрос
        error_log("Request: {$request->getMethod()} {$request->getUri()}");

        // 2. Выполняем обработку запроса
        $response = $handler->handle($request);

        // 3. Подготавливаем сообщение для вставки в тело ответа
        $logMessage = "Logged request: {$request->getMethod()} {$request->getUri()}\n";

        // 4. Добавляем лог в тело ответа
        $body = $response->getBody();
        $body->write("<hr>" . $logMessage);

        // 5. Возвращаем ответ с обновлённым телом
        return $response->withBody($body);
    }
}
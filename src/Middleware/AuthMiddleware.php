<?php


namespace PhpWeb\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

class AuthMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Проверяем авторизацию по сессии
        if (!isset($_SESSION['user'])) {
            $response = new SlimResponse();
            $response->getBody()->write('Unauthorized. Please login.');
            return $response->withStatus(401);
            // или редирект:
            // return $response
            //     ->withHeader('Location', '/login')
            //     ->withStatus(302);
        }

        return $handler->handle($request);
    }
}
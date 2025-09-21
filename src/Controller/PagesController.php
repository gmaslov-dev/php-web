<?php

namespace PhpWeb\Controller;
use Psr\Http\Message\ResponseInterface as Response;
class PagesController extends BaseController
{
    public function __invoke($request, $response, $next): Response {
        return $this->view->render($response, 'index.twig');
    }
}
<?php

namespace PhpWeb\Controller;

use Slim\Views\Twig;

class BaseController
{
    public function __construct(
        protected Twig $view,

    ) {}
}
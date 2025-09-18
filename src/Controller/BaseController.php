<?php

namespace PhpWeb\Controller;

use Slim\Views\Twig;
use Slim\Flash\Messages;
class BaseController
{
    public function __construct(
        protected Twig $view,
        protected Messages $flash
    ) {}
}
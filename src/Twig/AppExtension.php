<?php

namespace PhpWeb\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('myYear', fn() => date('Y')),
        ];
    }
}
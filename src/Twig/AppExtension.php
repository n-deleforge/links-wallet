<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
       return [
           new TwigFunction('get_env', [$this, 'getEnvironnementVariable']),
       ];
    }
    
    /**
     * Return the value of the requested environment variable.
     * 
     * @param String $varname
     * @return String
     */
    public function getEnvironnementVariable($variable)
    {
        return $_ENV[$variable];
    }
}
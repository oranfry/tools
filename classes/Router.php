<?php

namespace OranFry\Tools;

class Router extends \OranFry\Subsimple\Router
{
    protected static $routes = [
        'GET /' => [
            'PAGE' => 'tools/login',
            'AUTHSCHEME' => 'none',
            'LAYOUT' => 'login',
        ],

        'POST /ajax/auth/(?:login|logout)' => [
            'FORWARD' => \OranFry\Jars\HTTP\HttpRouter::class,
            'EAT' => '/ajax',
        ],
    ];
}

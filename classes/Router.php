<?php

namespace Tools;

class Router extends \subsimple\Router
{
    protected static $routes = [
        'GET /' => ['PAGE' => 'tools/login', 'AUTHSCHEME' => 'none', 'LAYOUT' => 'login'],
        'POST /ajax/auth/(?:login|logout)' => ['FORWARD' => \jars\http\HttpRouter::class, 'EAT' => '/ajax'],
   ];
}

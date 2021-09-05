<?php
namespace tools;

class Router extends \Router
{
    protected static $routes = [
        // login / logout

        'GET /' => ['PAGE' => 'tools/login', 'AUTHSCHEME' => 'none', 'LAYOUT' => 'main'],
        'GET /logout' => ['PAGE' => 'tools/logout', 'AUTHSCHEME' => 'none'],

        // special

        'POST /switch-user' => ['PAGE' => 'tools/switch-user', 'AUTHSCHEME' => 'cookie'],
   ];
}

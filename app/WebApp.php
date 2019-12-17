<?php
namespace App;

use Hae\Container;
use Hae\Exception\{
    MethodNotAllowedException,
    RouteNotFoundException
};

class WebApp
{
    public static $di;

    public function __construct()
    {
        static::$di = new Container();
    }

    public function handle($callback) {
        $request = self::$di->get('request');
        $response = self::$di->get('response');
        $router = new \Hae\Core\Router($request, $response);
        \call_user_func($callback, $router);
    }
}
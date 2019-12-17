<?php

require_once __DIR__.'/../vendor/autoload.php';

define('ROOT_PATH', __DIR__.'/..');
define('APP_PATH', ROOT_PATH.'/app');

$web = new \App\WebApp();
try {
    $web->handle(function($router) {
        
        $router->get('/', function() {
            echo json_encode(['message' => 'Hello Hae, this is my hometest']); 
        });

        $router->get('/home', [\App\Controller\IndexController::class, 'home']);
        $router->post('/login', [\App\Controller\IndexController::class, 'login']);

        // Guest book
        $router->get('/guestbook', [\App\Controller\GuestBookController::class, 'list']);
        $router->post('/guestbook', [\App\Controller\GuestBookController::class, 'create']);
        $router->get('/guestbook/{id}', [\App\Controller\GuestBookController::class, 'detail']);
        $router->put('/guestbook/{id}', [\App\Controller\GuestBookController::class, 'edit']);
        $router->delete('/guestbook/{id}', [\App\Controller\GuestBookController::class, 'delete']);
        
    
    });
} catch (\Hae\Exception\RouteNotFoundException $e) {
    $web::$di->get('response')->sendError404();
}
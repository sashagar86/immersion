<?php

if( !session_id() ) @session_start();

include '../vendor/autoload.php';

use DI\ContainerBuilder;
use League\Plates\Engine;
use Delight\Auth\Auth;

$containerBuilder = new ContainerBuilder();


$containerBuilder->addDefinitions([
    Engine::class => function(){
        return new Engine('../app/views');
    },

    Auth::class => function(){
        return new Auth(\DB\Connection::make());
    }
]);

$container = $containerBuilder->build();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute(['POST', 'GET'], '/registration', ['\App\Controllers\RegistrationController', 'register']);
    $r->addRoute('GET', '/verify-email', ['\App\Controllers\RegistrationController', 'verifyEmail']);

    $r->addRoute(['POST', 'GET'], '/login', ['\App\Controllers\AuthenticationController', 'login']);
    $r->addRoute('GET', '/logout', ['\App\Controllers\AuthenticationController', 'logout']);

    $r->addRoute('GET', '/user/{id:\d+}', ['\App\Controllers\UserController', 'show']);
    $r->addRoute(['GET', 'POST'], '/user/{id:\d+}/edit[/{action}]', ['\App\Controllers\UserController', 'edit']);
    $r->addRoute('GET', '/users', ['\App\Controllers\UserController', 'index']);
    $r->addRoute(['GET', 'POST'], '/user/add', ['\App\Controllers\UserController', 'create']);
    $r->addRoute('GET', '/user/remove/{id:\d+}', ['\App\Controllers\UserController', 'delete']);
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

$templates = new \League\Plates\Engine('../app/views');

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo $templates->render('404', ['title' => 'Page not found']);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo $templates->render('405', ['title' => 'Method not allowed']);
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $cont = $container->call($handler, $vars);
        break;
}
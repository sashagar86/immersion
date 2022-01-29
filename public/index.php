<?php

if( !session_id() ) @session_start();

include '../vendor/autoload.php';

use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder();
$container = $containerBuilder->build();
//$routes = [
//    '/' => '../homepage.php',
//    '/create-user' => '../create_user.php',
//    '/users' => '../users.php',
//    '/logout' => '../includes/logout.php',
//    '/login' => '../page_login.php',
//    '/registration' => '../page_register.php',
//    '/remove' => '../includes/remove.php'
//];
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute(['POST', 'GET'], '/registration', ['\App\Controllers\UserController', 'register']);
    $r->addRoute(['POST', 'GET'], '/login', ['\App\Controllers\UserController', 'login']);

    $r->addRoute('GET', '/logout', ['\App\Controllers\UserController', 'logout']);

    $r->addRoute('GET', '/verify-email', ['\App\Controllers\UserController', 'verifyEmail']);

    $r->addRoute('GET', '/user/{id:\d+}', ['\App\Controllers\UserController', 'show']);
    $r->addRoute(['GET', 'POST'], '/user/{id:\d+}/edit[/{action}]', ['\App\Controllers\UserController', 'edit']);
    $r->addRoute('GET', '/users', ['\App\Controllers\UserController', 'all']);
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

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo 404;
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo 405;
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $cont = $container->call($handler, $vars);
        break;
}
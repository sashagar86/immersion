<?php

session_start();

include '../vendor/autoload.php';

$routes = [
    '/' => '../homepage.php',
    '/create-user' => '../create_user.php',
    '/users' => '../users.php',
    '/logout' => '../includes/logout.php',
    '/login' => '../page_login.php',
    '/registration' => '../page_register.php',
    '/remove' => '../includes/remove.php'
];

$router = new \App\Router($routes);

$route = $router->get();

$templates = new League\Plates\Engine('../app/views');

echo $templates->render(ltrim($route));

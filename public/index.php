<?php

use Config\Router;
use Src\Api\UserController;
use Src\Api\SecurityController;
use Src\Api\PostController;

require dirname(__DIR__).'/config/bootstrap.php';

// Router
$router = new Router();

$router->respond("GET", "/", function() {
    include_once('../templates/index.html');
});

$router->respond("GET", "/index", function() {
    include_once('../templates/index.html');
});

$router->respond("GET", "/home", function() {
    include_once('../templates/index.html');
});

$router->respond("GET", "/api/v1/login", function() {
    $security = new SecurityController();
    $security->login();
});

$router->respond("GET", "/api/v1/logout", function() {
    $security = new SecurityController();
    $security->logout();
});

$router->respond("POST", "/api/v1/register", function() {
    $user = new UserController();
    $user->createUser();
});

$router->respond("GET", "/api/v1/graphs", function() {
    $graphs = new PostController();
    $graphs->showPost();
});

$router->respond("POST", "/api/v1/graphs", function() {
    $graphs = new PostController();
    $graphs->createPost();
});

$router->respond("PUT", "/api/v1/graphs", function() {
    $graphs = new PostController();
    $graphs->updatePost();
});

$router->respond("DELETE", "/api/v1/graphs", function() {
    $graphs = new PostController();
    $graphs->deletePost();
});

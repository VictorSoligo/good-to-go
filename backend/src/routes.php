<?php

use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Controllers;
use App\Middleware\AuthMiddleware;

return function (App $app) {
  $app->group('/users', function (Group $group) {
    $group->post('', Controllers\RegisterUserController::class . ':handle')->add(new AuthMiddleware());
    $group->post('/sessions', Controllers\AuthenticateUserController::class . ':handle');
  });
};

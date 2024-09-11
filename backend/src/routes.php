<?php

use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Controllers;
use App\Middleware\AuthMiddleware;
use App\Middleware\EnsureManagerMiddleware;

return function (App $app) {
  $app->group('/users', function (Group $group) {
    $group->post('', Controllers\RegisterUserController::class . ':handle');
    $group->post('/sessions', Controllers\AuthenticateUserController::class . ':handle');
  });

  $app->group('/stores', function (Group $group) {
    $group->post('', Controllers\CreateStoreController::class . ':handle')->add(new EnsureManagerMiddleware());
  })->add(new AuthMiddleware());
};

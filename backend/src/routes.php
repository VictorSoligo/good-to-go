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
    $group->get('/me', Controllers\GetUserProfileController::class . ':handle')->add(new AuthMiddleware());
  });

  $app->group('/stores', function (Group $group) {
    $group->post('', Controllers\CreateStoreController::class . ':handle')->add(new EnsureManagerMiddleware());
    $group->get('/owner', Controllers\FetchOwnerStoresController::class . ':handle');
    $group->get('/id/{id}', Controllers\GetStoreController::class . ':handle');
  })->add(new AuthMiddleware());

  $app->group('/offers', function (Group $group) {
    $group->post('', Controllers\CreateOfferController::class . ':handle')->add(new EnsureManagerMiddleware());
  })->add(new AuthMiddleware());
};

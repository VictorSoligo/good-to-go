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
    $group->get('', Controllers\FetchStoresController::class . ':handle');
    $group->post('', Controllers\CreateStoreController::class . ':handle')->add(new EnsureManagerMiddleware());
    $group->get('/owner', Controllers\FetchOwnerStoresController::class . ':handle');
    $group->get('/id/{id}', Controllers\GetStoreController::class . ':handle');
  })->add(new AuthMiddleware());

  $app->group('/offers', function (Group $group) {
    $group->get('/{id}/details', Controllers\GetOfferController::class . ':handle');
    $group->post('', Controllers\CreateOfferController::class . ':handle')->add(new EnsureManagerMiddleware());
    $group->patch('/{id}/cancel', Controllers\CancelOfferController::class . ':handle')->add(new EnsureManagerMiddleware());
    $group->get('/active', Controllers\FetchActiveOffersController::class . ':handle');
  })->add(new AuthMiddleware());

  $app->group('/attachments', function (Group $group) {
    $group->post('', Controllers\UploadAttachmentController::class . ':handle')->add(new EnsureManagerMiddleware())->add(new AuthMiddleware());
    $group->get('/{url}', Controllers\GetAttachmentController::class . ':handle');
  });
};

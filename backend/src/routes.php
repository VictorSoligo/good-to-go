<?php

use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Controllers;

return function (App $app) {
  $app->group('/users', function (Group $group) {
    $group->post('', Controllers\RegisterUserController::class . ':handle');
  });
};

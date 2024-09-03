<?php

use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Controllers;

return function (App $app) {
  $app->group('/users', function (Group $group) {
    $group->get('', Controllers\RegisterUserController::class . ':handle');
  });
};

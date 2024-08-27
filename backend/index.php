<?php

use App\Database\Connection;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/vendor/autoload.php';    
require __DIR__ . '/src/Env/index.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response) {
    $instance = Connection::getInstance();
    $mysql = $instance->getConnection();

    $stmt = $mysql->query("SELECT * FROM users");
    $users = $stmt->fetchAll();

    $data = ['users', $users];

    $payload = json_encode($data);

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();

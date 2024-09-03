<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RegisterUserController {
  function handle(Request $request, Response $response) {
    $payload = json_encode(['ok' => true]);

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
  }
}
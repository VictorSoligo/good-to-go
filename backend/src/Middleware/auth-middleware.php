<?php

namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

class AuthMiddleware {
  public function __invoke($request, $handler) {
    $errorResponse = new Response(401, new Headers(["Content-Type" => "application/json"]));
    $errorResponse->getBody()->write(json_encode(["message" => "Não autenticado"]));

    $response = $handler->handle($request);

    $authHeader = $request->getHeaderLine("Authorization");
    
    if (!$authHeader) {
      return $errorResponse;
    }

    list($token) = sscanf($authHeader, 'Bearer %s');

    try {
      $rawPayload = JWT::decode($token, new Key(getenv("JWT_SECRET"), 'HS256'));
      $payload = (array) $rawPayload;

      $request->withAttribute("userId", $payload['sub']);

      return $response;
    } catch (\Throwable $th) {
      return $errorResponse;
    } 
  }
}
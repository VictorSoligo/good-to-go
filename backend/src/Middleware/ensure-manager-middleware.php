<?php

namespace App\Middleware;

use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use App\Database\Connection;

class EnsureManagerMiddleware {
  public function __invoke($request, $handler) {
    $errorResponse = new Response(403, new Headers(["Content-Type" => "application/json"]));
    $errorResponse->getBody()->write(json_encode(["message" => "Não autorizado"]));

    try {
      $userId = $request->getAttribute('userId');

      $instance = Connection::getInstance();
      $mysql = $instance->getConnection();

      $sql = <<<SQL
        SELECT
          id,
          role
        FROM
          users
        WHERE
          id = ?
      SQL;

      $stmt = $mysql->prepare($sql);
      $stmt->execute([$userId]);

      $userData = $stmt->fetch();

      if ($userData["role"] !== "manager") {
        return $errorResponse;
      }
      
      $response = $handler->handle($request);

      return $response;
    } catch (\Throwable $th) {
      return $errorResponse;
    } 
  }
}

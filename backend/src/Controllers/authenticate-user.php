<?php

namespace App\Controllers;

use App\Database\Repositories\UsersRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;

class AuthenticateUserController {
  private UsersRepository $usersRepository;

  public function __construct(UsersRepository $usersRepository) {
    $this->usersRepository = $usersRepository;
  }

  function handle(Request $request, Response $response) {
    $body = $request->getParsedBody();

    $email = $body['email'];
    $password = $body['password'];

    $user = $this->usersRepository->findByEmail($email);

    if (!$user) {
      $response->getBody()->write(json_encode(
        ["message" => "Credenciais inválidas"]
      ));

      return $response->withStatus(400);
    }

    $isPasswordValid = password_verify($password, $user->password);

    if (!$isPasswordValid) {
      $response->getBody()->write(json_encode(
        ["message" => "Credenciais inválidas"]
      ));

      return $response->withStatus(400);
    }

    $iat = time(); 
    $exp = $iat + 60 * 60 * 24 * 7; // 7 days

    $payload = [
      "sub" => $user->id,
      "iat" => $iat, 
      "exp" => $exp, 
    ];

    $accessToken = JWT::encode($payload, getenv("JWT_SECRET"), 'HS256');

    $response->getBody()->write(json_encode([
      "accessToken" => $accessToken,
    ]));

    return $response->withStatus(201);
  }
}
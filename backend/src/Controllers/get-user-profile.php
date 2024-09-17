<?php

namespace App\Controllers;

use App\Database\Repositories\UsersRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetUserProfileController {
  private UsersRepository $usersRepository;

  public function __construct(UsersRepository $usersRepository) {
    $this->usersRepository = $usersRepository;
  }

  function handle(Request $request, Response $response) {
    $userId = $request->getAttribute('userId');

    $user = $this->usersRepository->findById($userId);

    if (!$user) {
      $response->getBody()->write(json_encode(
        ["message" => "Usuário não encontrado"]
      ));

      return $response->withStatus(404);
    }

    $response->getBody()->write(json_encode(
      ["user" => $user]
    ));

    return $response->withStatus(200);
  }
}
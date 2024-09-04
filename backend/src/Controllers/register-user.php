<?php

namespace App\Controllers;

use App\Database\Repositories\UsersRepository;
use App\Domain\Entities\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RegisterUserController {
  private UsersRepository $usersRepository;

  public function __construct(UsersRepository $usersRepository) {
    $this->usersRepository = $usersRepository;
  }

  function handle(Request $request, Response $response) {
    $body = $request->getParsedBody();

    $user = new User(null, $body['name'], $body['email'], $body['password']);

    $this->usersRepository->create($user);

    return $response->withStatus(201);
  }
}
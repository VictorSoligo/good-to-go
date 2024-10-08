<?php

namespace App\Controllers;

use App\Database\Repositories\UsersRepository;
use App\Domain\Entities\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Queue\RabbitMq;

class RegisterUserController {
  private UsersRepository $usersRepository;

  public function __construct(UsersRepository $usersRepository) {
    $this->usersRepository = $usersRepository;
  }

  function handle(Request $request, Response $response) {
    $body = $request->getParsedBody();

    $name = $body['name'];
    $email = $body['email'];
    $role = $body['role'];

    $userWithSameEmail = $this->usersRepository->findByEmail($email);

    if ($userWithSameEmail) {
      $response->getBody()->write(json_encode(
        ["message" => "Usuário já cadastrado"]
      ));

      return $response->withStatus(409);
    }

    $passwordHash = password_hash($body['password'], PASSWORD_BCRYPT);

    $user = new User(null, $name, $email, $passwordHash, $role, null);

    $this->usersRepository->create($user);

    $rabbitMq = RabbitMq::getInstance();

    $rabbitMq->publish("user-registration", [
      "id" => $user->id,
      "name" => $user->name,
      "email" => $user->email,
    ]);

    return $response->withStatus(201);
  }
}
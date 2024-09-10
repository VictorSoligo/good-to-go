<?php

namespace App\Database\Repositories;

use App\Domain\Entities\User;
use PDO;

class UsersRepository {
  private PDO $mysql;
  
  public function __construct(PDO $mysql) {
    $this->mysql = $mysql;
  }

  public function findById(string $id) {
    $sql = "
      SELECT
        id,
        name,
        password_hash,
        role,
        email,
        created_at
      FROM
        users
      WHERE
        id = ?
    ";

    $stmt = $this->mysql->prepare($sql);
    $stmt->execute([$id]);

    $userData = $stmt->fetch();
    
    if (!isset($userData)) {
      return null;
    }

    $user = new User(
      $userData["id"], 
      $userData["name"], 
      $userData["email"], 
      $userData["password_hash"], 
      $userData["role"]
    );

    return $user;
  }

  public function findByEmail(string $email) {
    $sql = "
      SELECT
        id,
        name,
        password_hash,
        role,
        email,
        created_at
      FROM
        users
      WHERE
        email = ?
    ";

    $stmt = $this->mysql->prepare($sql);
    $stmt->execute([$email]);

    $userData = $stmt->fetch();
    
    if (!$userData) {
      return null;
    }

    $user = new User(
      $userData["id"], 
      $userData["name"], 
      $userData["email"], 
      $userData["password_hash"], 
      $userData["role"]
    );

    return $user;
  }

  public function create(User $user) {
    $sql = "
      INSERT INTO
        users
        (
          id,
          name,
          email,
          password_hash,
          role,
          created_at
        )
      VALUES
        (
          ?,
          ?,
          ?,
          ?,
          ?,
          ?
        )
    ";

    $stmt = $this->mysql->prepare($sql);

    $stmt->execute([
      $user->id,
      $user->name,
      $user->email,
      $user->password,
      $user->role,
      date("Y-m-d H:i:s"),
    ]);
  }
}

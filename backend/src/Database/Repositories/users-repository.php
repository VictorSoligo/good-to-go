<?php

namespace App\Database\Repositories;

use App\Domain\Entities\Date;
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

    $data = $stmt->fetch();
    
    if (!isset($data)) {
      return null;
    }

    $user = new User(
      $data["id"], 
      $data["name"], 
      $data["email"], 
      $data["password_hash"], 
      $data["role"],
      new Date($data["created_at"]),
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

    $data = $stmt->fetch();
    
    if (!$data) {
      return null;
    }

    $user = new User(
      $data["id"], 
      $data["name"], 
      $data["email"], 
      $data["password_hash"], 
      $data["role"],
      new Date($data["created_at"]),
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
      $user->createdAt->format("c"),
    ]);
  }
}

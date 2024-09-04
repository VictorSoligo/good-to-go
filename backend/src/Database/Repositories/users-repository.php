<?php

namespace App\Database\Repositories;

use App\Domain\Entities\User;
use PDO;

class UsersRepository {
  private PDO $mysql;
  
  public function __construct(PDO $mysql) {
    $this->mysql = $mysql;
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
          created_at,
          role
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
      date("Y-m-d H:i:s"),
      "manager",
    ]);
  }
}

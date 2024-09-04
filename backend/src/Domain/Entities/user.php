<?php

namespace App\Domain\Entities;

class User {
  public string $id;
  public string $name;
  public string $email;
  public string $password;

  public function __construct(?string $id, string $name, string $email, string $password) {
    $this->id = isset($id) ? $id : uniqid();
    $this->name = $name;
    $this->email = $email;
    $this->password = $password;
  }
}

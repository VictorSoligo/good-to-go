<?php

namespace App\Domain\Entities;

class User implements \JsonSerializable {
  public string $id;
  public string $name;
  public string $email;
  public string $password;
  public string $role;
  public Date $createdAt;

  public function __construct(
    ?string $id, 
    string $name, 
    string $email, 
    string $password, 
    string $role, 
    ?Date $createdAt
  ) {
    $this->id = isset($id) ? $id : uniqid();
    $this->name = $name;
    $this->email = $email;
    $this->password = $password;
    $this->role = $role;
    $this->createdAt = isset($createdAt) ? $createdAt : new Date();
  }

  public function jsonSerialize(): mixed {
    return [
      "id" => $this->id,
      "name" => $this->name,
      "email" => $this->email,
      "role" => $this->role,
      "createdAt" => $this->createdAt,
    ];
  }
}

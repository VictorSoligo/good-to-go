<?php

namespace App\Domain\Entities;

class Store {
  public string $id;
  public string $name;
  public string $adress;
  public string $ownerId;

  public function __construct(?string $id, string $name, string $adress, string $ownerId) {
    $this->id = isset($id) ? $id : uniqid();
    $this->name = $name;
    $this->adress = $adress;
    $this->ownerId = $ownerId;
  }
}

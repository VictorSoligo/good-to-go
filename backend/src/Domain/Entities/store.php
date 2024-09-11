<?php

namespace App\Domain\Entities;

class Store {
  public string $id;
  public string $name;
  public string $adress;

  public function __construct(?string $id, string $name, string $adress) {
    $this->id = isset($id) ? $id : uniqid();
    $this->name = $name;
    $this->adress = $adress;
  }
}

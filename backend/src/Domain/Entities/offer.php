<?php 

namespace App\Domain\Entities;

use DateTimeImmutable;

class Offer {
  public string $id;
  public DateTimeImmutable $availableUntil;

  public function __construct(?string $id) {
    $this->id = isset($id) ? $id : uniqid();
  }
} 

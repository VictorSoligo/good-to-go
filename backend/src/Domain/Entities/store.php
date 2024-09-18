<?php

namespace App\Domain\Entities;

class Store {
  public string $id;
  public string $name;
  public string $adress;
  public string $ownerId;
  public string $attachmentId;
  public Date $createdAt;

  public function __construct(
    ?string $id,
    string $name,
    string $adress,
    string $ownerId, 
    string $attachmentId,
    ?Date $createdAt
  ) {
    $this->id = isset($id) ? $id : uniqid();
    $this->name = $name;
    $this->adress = $adress;
    $this->ownerId = $ownerId;
    $this->attachmentId = $attachmentId;
    $this->createdAt = isset($createdAt) ? $createdAt : new Date();
  }
}

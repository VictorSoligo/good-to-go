<?php 

namespace App\Domain\Entities;

class Offer {
  public string $id;
  public string $storeId;
  public string $description;
  public int $price; 
  public Date $availableUntil;
  public array $attachments;
  public ?Date $canceledAt;
  public Date $createdAt;

  public function __construct(
    ?string $id,
    string $storeId,
    string $description,
    int $price,
    array $attachments,
    Date $availableUntil,
    ?Date $canceledAt,
    ?Date $createdAt
  ) {
    $this->id = isset($id) ? $id : uniqid();
    $this->storeId = $storeId;
    $this->description = $description;
    $this->price = $price;
    $this->attachments = $attachments;
    $this->availableUntil = $availableUntil;
    $this->canceledAt = isset($canceledAt) ? $canceledAt : null;
    $this->createdAt = isset($createdAt) ? $createdAt : new Date();
  }

  public function cancel() {
    $this->canceledAt = new Date();
  }
} 

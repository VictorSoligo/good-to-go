<?php

namespace App\Domain\Entities;

class Attachment {
  public string $id;
  public string $userId;
  public string $type;
  public string $url;
  public Date $createdAt;

  public function __construct(
    ?string $id,
    string $userId,
    string $type,
    string $url,
    ?Date $createdAt,
  ) {
    $this->id = isset($id) ? $id : uniqid();
    $this->userId = $userId;
    $this->type = $type;
    $this->url = $url;
    $this->createdAt = isset($createdAt) ? $createdAt : new Date();
    
  }
}

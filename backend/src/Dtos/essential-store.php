<?php

namespace App\Dtos;

use App\Domain\Entities\Date;

class EssentialStore implements \JsonSerializable {
  public string $id;
  public string $name;
  public string $adress;
  public string $ownerId;
  public string $attachmentId;
  public string $attachmentUrl;
  public Date $createdAt;

  public function __construct(
    string $id,
    string $name,
    string $adress,
    string $ownerId, 
    string $attachmentId,
    string $attachmentUrl,
    Date $createdAt
  ) {
    $this->id = $id;
    $this->name = $name;
    $this->adress = $adress;
    $this->ownerId = $ownerId;
    $this->attachmentId = $attachmentId;
    $this->attachmentUrl = $attachmentUrl;
    $this->createdAt = $createdAt;
  }

  public function jsonSerialize(): mixed {
    return [
      "id" => $this->id,
      "name" => $this->name,
      "adress" => $this->adress,
      "ownerId" => $this->ownerId,
      "attachment" => [
        "id" => $this->attachmentId,
        "url" => $this->attachmentUrl,
      ],
      "createdAt" => $this->createdAt,
    ];
  }
}

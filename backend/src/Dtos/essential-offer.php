<?php

namespace App\Dtos;

use App\Domain\Entities\Date;

class EssentialOffer implements \JsonSerializable {
  public string $id;
  public string $storeId;
  public string $storeName;
  public string $description;
  public Date $availableUntil;
  public ?Date $canceledAt;
  public Date $createdAt;

  public function __construct(
    string $id,
    string $storeId,
    string $storeName,
    string $description,
    Date $availableUntil,
    ?Date $canceledAt,
    Date $createdAt,
  ) {
    $this->id = $id;
    $this->storeId = $storeId;
    $this->storeName = $storeName;
    $this->description = $description;
    $this->availableUntil = $availableUntil;
    $this->canceledAt = $canceledAt;
    $this->createdAt = $createdAt;
  }

  public function jsonSerialize(): mixed {
    return [
      "id" => $this->id,
      "description" => $this->description,
      "store" => [
        "id" => $this->storeId,
        "name" => $this->storeName
      ],
      "availableUntil" => $this->availableUntil,
      "createdAt" => $this->createdAt,
      "canceledAt" => $this->canceledAt,
    ];
  }
}

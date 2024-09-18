<?php

namespace App\Dtos;

use App\Domain\Entities\Date;

class EssentialOffer implements \JsonSerializable {
  public string $id;
  public string $storeId;
  public string $storeName;
  public string $description;
  public int $price;
  public array $attachments;
  public Date $availableUntil;
  public ?Date $canceledAt;
  public Date $createdAt;

  public function __construct(
    string $id,
    string $storeId,
    string $storeName,
    string $description,
    int $price,
    array $attachments,
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
    $this->attachments = $attachments;
    $this->createdAt = $createdAt;
    $this->price = $price;
  }

  public function jsonSerialize(): mixed {
    $attachments = array_map(function ($attachment) {
      return [
        "id" => $attachment["id"],
        "url" => $attachment["url"],
      ];
    }, $this->attachments);

    return [
      "id" => $this->id,
      "description" => $this->description,
      "price" => $this->price,
      "store" => [
        "id" => $this->storeId,
        "name" => $this->storeName
      ],
      "attachments" => $attachments,
      "availableUntil" => $this->availableUntil,
      "canceledAt" => $this->canceledAt,
      "createdAt" => $this->createdAt,
    ];
  }
}

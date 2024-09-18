<?php

namespace App\Domain\Entities;

class OfferAttachment {
  public string $id;
  public string $offerId;
  public string $attachmentId;

  public function __construct(
    ?string $id,
    string $offerId,
    string $attachmentId,
  ) {
    $this->id = isset($id) ? $id : uniqid();
    $this->offerId = $offerId;
    $this->attachmentId = $attachmentId;
  }
}

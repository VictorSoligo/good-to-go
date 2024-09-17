<?php

namespace App\Domain\Entities;

class Date extends \DateTime implements \JsonSerializable {
  public function jsonSerialize(): mixed {
    return $this->format("c");
  }
}

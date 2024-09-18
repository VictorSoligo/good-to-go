<?php

namespace App\Database\Repositories;

use App\Domain\Entities\Attachment;
use PDO;

class AttachmentsRepository {
  private PDO $mysql;
  
  public function __construct(PDO $mysql) {
    $this->mysql = $mysql;
  }

  public function create(Attachment $attachment) {
    $sql = <<<SQL
      INSERT INTO
        attachments
        (
          id,
          user_id,
          type,
          url,
          created_at
        )
      VALUES
        (
          ?,
          ?,
          ?,
          ?,
          ?
        )
    SQL;

    $stmt = $this->mysql->prepare($sql);

    $stmt->execute([
      $attachment->id,
      $attachment->userId,
      $attachment->type,
      $attachment->url,
      $attachment->createdAt->format("c"),
    ]);
  }
}

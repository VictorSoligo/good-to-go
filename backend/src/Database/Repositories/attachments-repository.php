<?php

namespace App\Database\Repositories;

use App\Domain\Entities\Attachment;
use App\Domain\Entities\Date;
use PDO;

class AttachmentsRepository {
  private PDO $mysql;
  
  public function __construct(PDO $mysql) {
    $this->mysql = $mysql;
  }

  public function findById(string $id) {
    $sql = <<<SQL
      SELECT
        id,
        user_id,
        type,
        url,
        created_at
      FROM
        attachments
      WHERE
        id = ?  
    SQL;

    $stmt = $this->mysql->prepare($sql);
    $stmt->execute([$id]);

    $data = $stmt->fetch();

    if (!$data) {
      return null;
    }

    $attachment = new Attachment(
      $data["id"],
      $data["user_id"],
      $data["type"],
      $data["url"],
      new Date($data["created_at"]),
    );

    return $attachment;
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

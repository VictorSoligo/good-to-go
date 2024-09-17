<?php

namespace App\Database\Repositories;

use App\Domain\Entities\Offer;
use App\Domain\Entities\Date;
use PDO;

class OffersRepository {
  private PDO $mysql;
  
  public function __construct(PDO $mysql) {
    $this->mysql = $mysql;
  }

  public function findById(string $id) {
    $sql = <<<SQL
      SELECT
        id,
        store_id,
        description,
        available_until,
        canceled_at,
        created_at
      FROM
        offers
      WHERE
        id = ?
    SQL;

    $stmt = $this->mysql->prepare($sql);
    $stmt->execute([$id]);

    $data = $stmt->fetch();
    
    if (!$data) {
      return null;
    }

    $offer = new Offer(
      $data["id"], 
      $data["store_id"], 
      $data["description"], 
      new Date($data["available_until"]),
      $data["canceled_at"] ? new Date($data["canceled_at"]) : null,
      new Date($data["created_at"]),
    );

    return $offer;
  }

  public function findManyByStoreId(string $storeId) {
    $sql = <<<SQL
      SELECT
        id,
        store_id,
        description,
        available_until,
        canceled_at,
        created_at
      FROM
        offers
      WHERE
        store_id = ?
    SQL;

    $stmt = $this->mysql->prepare($sql);
    $stmt->execute([$storeId]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $offers = [];

    if (count($data) > 0) {
      foreach ($data as $s) {
        $offer = new Offer(
          $s["id"], 
          $s["store_id"], 
          $s["description"], 
          new Date($s["available_until"]),
          $s["canceled_at"] ? new Date($s["canceled_at"]) : null,
          new Date($s["created_at"]),
        );

        array_push($offers, $offer);
      }
    }

    return $offers;
  }

  public function create(Offer $offer) {
    $sql = <<<SQL
      INSERT INTO
        offers
        (
          id,
          store_id,
          description,
          available_until,
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
      $offer->id,
      $offer->storeId,
      $offer->description,
      $offer->availableUntil->format("c"),
      $offer->createdAt->format("c"),
    ]);
  }
  
  public function save(Offer $offer) {
    $sql = <<<SQL
      UPDATE
        offers
      SET
        canceled_at = ?
      WHERE
        id = ?
    SQL;

    $stmt = $this->mysql->prepare($sql);

    $stmt->execute([
      $offer->canceledAt ? $offer->canceledAt->format("c") : null,
      $offer->id,
    ]);
  }
} 

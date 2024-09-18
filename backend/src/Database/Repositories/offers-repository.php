<?php

namespace App\Database\Repositories;

use App\Domain\Entities\Offer;
use App\Dtos\EssentialOffer;
use App\Domain\Entities\Date;
use App\Domain\Entities\OfferAttachment;
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
        price,
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

    $offerId = $data["id"];

    $attachmentsSql = <<<SQL
      SELECT
        id,
        offer_id,
        attachment_id
      FROM
        offers_attachments
      WHERE
        offer_id = ?
    SQL;

    $stmt = $this->mysql->prepare($attachmentsSql);
    $stmt->execute([$offerId]);

    $attachmentsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$attachmentsData || count($attachmentsData) === 0) {
      return null;
    }

    $attachments = [];

    foreach ($attachmentsData as $a) {
      $offerAttachment = new OfferAttachment(
        $a["id"],
        $a["offer_id"],
        $a["attachment_id"],
      );

      array_push($attachments, $offerAttachment);
    }

    $offer = new Offer(
      $offerId, 
      $data["store_id"], 
      $data["description"], 
      $data["price"],
      $attachments,
      new Date($data["available_until"]),
      $data["canceled_at"] ? new Date($data["canceled_at"]) : null,
      new Date($data["created_at"]),
    );

    return $offer;
  }

  public function findManyActive() {
    $sql = <<<SQL
      SELECT
        offers.id,
        offers.store_id,
        offers.description,
        offers.available_until,
        offers.canceled_at,
        offers.created_at,
        offers.price,
        stores.name AS store_name
      FROM
        offers
      INNER JOIN stores ON stores.id = offers.store_id
      WHERE
        offers.canceled_at IS NULL
      AND
        offers.available_until > NOW()
      ORDER BY
        offers.created_at DESC
    SQL;

    $stmt = $this->mysql->prepare($sql);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $offers = [];

    if (count($data) > 0) {
      foreach ($data as $s) {
        $offer = new EssentialOffer(
          $s["id"], 
          $s["store_id"],
          $s["store_name"],
          $s["description"],
          $s["price"], 
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
          created_at,
          price
        )
      VALUES
        (
          ?,
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
      $offer->price,
    ]);

    foreach ($offer->attachments as $attachment) {
      $attachmentsSql = <<<SQL
        INSERT INTO
          offers_attachments
          (
            id,
            offer_id,
            attachment_id
          )
        VALUES
          (
            ?,
            ?,
            ?
          )
      SQL;

      $stmt = $this->mysql->prepare($attachmentsSql);

      $stmt->execute([
        $attachment->id,
        $attachment->offerId,
        $attachment->attachmentId,
      ]);
    }
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

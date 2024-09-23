<?php

namespace App\Database\Repositories;

use App\Domain\Entities\Store;
use App\Domain\Entities\Date;
use App\Dtos\EssentialStore;
use PDO;

class StoresRepository {
  private PDO $mysql;
  
  public function __construct(PDO $mysql) {
    $this->mysql = $mysql;
  }

  public function findById(string $id) {
    $sql = "
      SELECT
        id,
        name,
        adress,
        owner_id,
        created_at,
        attachment_id
      FROM
        stores
      WHERE
        id = ?
    ";

    $stmt = $this->mysql->prepare($sql);
    $stmt->execute([$id]);

    $data = $stmt->fetch();
    
    if (!$data) {
      return null;
    }

    $store = new Store(
      $data["id"], 
      $data["name"], 
      $data["adress"], 
      $data["owner_id"],
      $data["attachment_id"],
      new Date($data["created_at"]),
    );

    return $store;
  }

  public function findEssentialById(string $id) {
    $sql = "
      SELECT
        stores.id,
        stores.name,
        stores.adress,
        stores.owner_id,
        stores.created_at,
        stores.attachment_id,
        attachments.url AS attachment_url
      FROM
        stores
      INNER JOIN attachments ON attachments.id = stores.attachment_id
      WHERE
        stores.id = ?
    ";

    $stmt = $this->mysql->prepare($sql);
    $stmt->execute([$id]);

    $data = $stmt->fetch();
    
    if (!$data) {
      return null;
    }

    $store = new EssentialStore(
      $data["id"], 
      $data["name"], 
      $data["adress"], 
      $data["owner_id"],
      $data["attachment_id"],
      $data["attachment_url"],
      new Date($data["created_at"]),
    );

    return $store;
  }

  public function findByName(string $name) {
    $sql = "
      SELECT
        id,
        name,
        adress,
        owner_id,
        created_at,
        attachment_id
      FROM
        stores
      WHERE
        name = ?
    ";

    $stmt = $this->mysql->prepare($sql);
    $stmt->execute([$name]);

    $data = $stmt->fetch();
    
    if (!$data) {
      return null;
    }

    $store = new Store(
      $data["id"], 
      $data["name"], 
      $data["adress"],
      $data["owner_id"], 
      $data["attachment_id"],
      new Date($data["created_at"]),
    );

    return $store;
  }

  public function findMany() {
    $sql = "
      SELECT
        stores.id,
        stores.name,
        stores.adress,
        stores.owner_id,
        stores.created_at,
        stores.attachment_id,
        attachments.url AS attachment_url
      FROM
        stores
      INNER JOIN attachments ON attachments.id = stores.attachment_id
      ORDER BY
        stores.created_at DESC
    ";

    $stmt = $this->mysql->prepare($sql);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stores = [];

    if (count($data) > 0) {
      foreach ($data as $s) {
        $store = new EssentialStore(
          $s["id"], 
          $s["name"], 
          $s["adress"], 
          $s["owner_id"],
          $s["attachment_id"],
          $s["attachment_url"],
          new Date($s["created_at"]),
        );

        array_push($stores, $store);
      }
    }

    return $stores;
  }

  public function findManyByOwnerId(string $ownerId) {
    $sql = "
      SELECT
        stores.id,
        stores.name,
        stores.adress,
        stores.owner_id,
        stores.created_at,
        stores.attachment_id,
        attachments.url AS attachment_url
      FROM
        stores
      INNER JOIN attachments ON attachments.id = stores.attachment_id
      WHERE
        stores.owner_id = ?
      ORDER BY
        stores.created_at DESC
    ";

    $stmt = $this->mysql->prepare($sql);
    $stmt->execute([$ownerId]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stores = [];

    if (count($data) > 0) {
      foreach ($data as $s) {
        $store = new EssentialStore(
          $s["id"], 
          $s["name"], 
          $s["adress"], 
          $s["owner_id"],
          $s["attachment_id"],
          $s["attachment_url"],
          new Date($s["created_at"]),
        );

        array_push($stores, $store);
      }
    }

    return $stores;
  }

  public function create(Store $store) {
    $sql = "
      INSERT INTO
        stores
        (
          id,
          name,
          adress,
          created_at,
          owner_id,
          attachment_id
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
    ";

    $stmt = $this->mysql->prepare($sql);

    $stmt->execute([
      $store->id,
      $store->name,
      $store->adress,
      $store->createdAt->format("c"),
      $store->ownerId,
      $store->attachmentId,
    ]);
  } 
} 

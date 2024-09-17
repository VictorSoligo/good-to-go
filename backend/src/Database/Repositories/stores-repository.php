<?php

namespace App\Database\Repositories;

use App\Domain\Entities\Store;
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
        created_at
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
        created_at
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
    );

    return $store;
  }

  public function findManyByOwnerId(string $ownerId) {
    $sql = "
      SELECT
        id,
        name,
        adress,
        owner_id,
        created_at
      FROM
        stores
      WHERE
        owner_id = ?
    ";

    $stmt = $this->mysql->prepare($sql);
    $stmt->execute([$ownerId]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stores = [];

    if (count($data) > 0) {
      foreach ($data as $s) {
        $store = new Store(
          $s["id"], 
          $s["name"], 
          $s["adress"],
          $s["owner_id"],
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
          owner_id
        )
      VALUES
        (
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
      date("Y-m-d H:i:s"),
      $store->ownerId,
    ]);
  } 
} 

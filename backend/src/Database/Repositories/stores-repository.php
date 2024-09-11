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
        created_at
      FROM
        stores
      WHERE
        id = ?
    ";

    $stmt = $this->mysql->prepare($sql);
    $stmt->execute([$id]);

    $data = $stmt->fetch();
    
    if (!isset($data)) {
      return null;
    }

    $store = new Store(
      $data["id"], 
      $data["name"], 
      $data["adress"], 
    );

    return $store;
  }

  public function findByName(string $name) {
    $sql = "
      SELECT
        id,
        name,
        adress,
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
    );

    return $store;
  }

  public function create(Store $store) {
    $sql = "
      INSERT INTO
        stores
        (
          id,
          name,
          adress,
          created_at
        )
      VALUES
        (
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
    ]);
  } 
} 

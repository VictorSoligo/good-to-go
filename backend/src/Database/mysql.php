<?php

namespace App\Database;

use PDO;
use PDOException;

class Connection {
  private static $instance = null;
  private $connection;

  private function __construct() {
    $host = getenv("MYSQL_HOST");
    $database = getenv("MYSQL_DATABASE");
    $user = getenv("MYSQL_USER");
    $password = getenv("MYSQL_PASSWORD");

    try {
      $this->connection = new PDO("mysql:host=$host;dbname=$database", $user, $password);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
    } catch (PDOException $e) {
      die("Database connection failed: " . $e->getMessage());
    }
  }

  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new Connection();
    }

    return self::$instance;
  }

  public function getConnection() {
    return $this->connection;
  }
}

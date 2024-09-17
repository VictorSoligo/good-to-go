<?php

namespace App\Queue;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMq {
  private static $instance = null;
  private AMQPStreamConnection $connection;
  private AMQPChannel $channel;

  private function __construct() {
    $host = getenv("RABBITMQ_HOST");
    $user = getenv("RABBITMQ_USER");
    $password = getenv("RABBITMQ_PASSWORD");

    try {
      $this->connection = new AMQPStreamConnection($host, 5672, $user, $password);
      $this->channel = $this->connection->channel();

      $this->channel->exchange_declare('good-to-go', 'topic');
      $this->channel->queue_declare("user-registration");

      $this->channel->queue_bind("user-registration", "good-to-go", "user-registration");

    } catch (Exception $e) {
      die("Database connection failed: " . $e->getMessage());
    }
  }

  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new RabbitMq();
    }

    return self::$instance;
  }

  public function publish(string $queue, array $payload) {
    $msg = new AMQPMessage(json_encode($payload));

    $this->channel->basic_publish($msg, "good-to-go", $queue);
  }
}

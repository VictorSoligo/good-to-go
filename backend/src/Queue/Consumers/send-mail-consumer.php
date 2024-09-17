<?php

require '../../../vendor/autoload.php';
require '../../Env/index.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PHPMailer\PHPMailer\PHPMailer;

function consume($msg) {
  $payload = json_decode($msg->body, true);

  $smtpHost = getenv("SMTP_HOST");
  $smtpPort = getenv("SMTP_PORT");
  $sender = getenv("SMTP_SENDER_ADRESS");

  $mailer = new PHPMailer(true);

  $mailer->isSMTP();
  $mailer->CharSet = "UTF-8";
  $mailer->Host = $smtpHost;
  $mailer->Port = $smtpPort;
  $mailer->setFrom($sender);

  $subject = "Nova inscrição no GoodToGo";
  
  $mailer->isHTML(true);   
  $mailer->Subject = $subject;
  $mailer->addAddress($payload['email']);

  $name = $payload['name'];

  $mailer->Body = "<h1>$name, seja muito bem-vindo(a) ao GoodToGo!</h1>";

  $mailer->send();
}

$host = getenv("RABBITMQ_HOST");
$user = getenv("RABBITMQ_USER");
$password = getenv("RABBITMQ_PASSWORD");

$rabbitmq = new AMQPStreamConnection($host, 5672, $user, $password);

$channel = $rabbitmq->channel();

$channel->exchange_declare('good-to-go', 'topic');
$channel->queue_declare("user-registration");
$channel->queue_bind("user-registration", "good-to-go", "user-registration");

$channel->basic_consume(
  "user-registration", 
  '', 
  false, 
  true, 
  false, 
  false,
  function ($msg) {
    consume($msg);
  }
);

while ($channel->is_consuming()) {
  $channel->wait();
}


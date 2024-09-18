<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetAttachmentController {
  function handle(Request $request, Response $response, array $args) {
    $url = $args["url"];

    $filePath = $_SERVER["DOCUMENT_ROOT"] . "/uploads/" . $url;

    if (!file_exists($filePath)) {
      $response->getBody()->write(json_encode(
        ["message" => "Midia não encontrada"]
      ));

      return $response->withStatus(404);
    }

    $response = $response->withHeader("Content-Type", mime_content_type($filePath));
    $response = $response->withHeader("Content-Length", filesize($filePath));

    $stream = fopen($filePath, 'rb');

    $response->getBody()->write(stream_get_contents($stream));

    fclose($stream);

    return $response->withStatus(200);
  }
}
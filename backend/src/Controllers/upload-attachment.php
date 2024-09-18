<?php

namespace App\Controllers;

use App\Database\Repositories\AttachmentsRepository;
use App\Domain\Entities\Attachment;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\UploadedFile;

class UploadAttachmentController {
  private AttachmentsRepository $attachmentsRepository;

  public function __construct(AttachmentsRepository $attachmentsRepository) {
    $this->attachmentsRepository = $attachmentsRepository;
  }

  function handle(Request $request, Response $response) {
    $userId = $request->getAttribute('userId');

    $uploadedFiles = $request->getUploadedFiles();
    
    $content = isset($uploadedFiles["file"]) ? $uploadedFiles["file"] : null;
    
    if (!$content) {
      $response->getBody()->write(json_encode(
        ["message" => "Midia não encontrada na requisição"]
      ));

      return $response->withStatus(400);
    }

    $fileType = explode(', ', $content->getClientMediaType());
    $allowedMediaTypes = ['image/jpeg', 'image/png', 'image/jpg'];

    if (!array_intersect($fileType, $allowedMediaTypes)) {
      $response->getBody()->write(json_encode(
        ["message" => "Midia não suportada"]
      ));

      return $response->withStatus(400);
    }

    $type = pathinfo($content->getClientFilename(), PATHINFO_EXTENSION);
    $url = uniqid() . "." . $type;

    moveUploadedFile($content, $url);

    $attachment = new Attachment(
      null,
      $userId,
      $type,
      $url,
      null
    );

    $this->attachmentsRepository->create($attachment);

    $response->getBody()->write(json_encode(
      ["id" => $attachment->id]
    ));

    return $response->withStatus(201);
  }
}

function moveUploadedFile(UploadedFile $uploadedFile, string $url, ) {
  $dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';

  $uploadedFile->moveTo($dir . $url);
}

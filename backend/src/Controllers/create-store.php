<?php

namespace App\Controllers;

use App\Database\Repositories\AttachmentsRepository;
use App\Database\Repositories\StoresRepository;
use App\Domain\Entities\Store;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateStoreController {
  private StoresRepository $storesRepository;
  private AttachmentsRepository $attachmentsRepository;

  public function __construct(
    StoresRepository $storesRepository, 
    AttachmentsRepository $attachmentsRepository
  ) {
    $this->storesRepository = $storesRepository;
    $this->attachmentsRepository = $attachmentsRepository;
  }

  function handle(Request $request, Response $response) {
    $body = $request->getParsedBody();

    $name = $body['name'];
    $adress = $body['adress'];
    $attachmentId = $body['attachmentId'];
    $userId = $request->getAttribute('userId');

    $storeWithSameName = $this->storesRepository->findByName($name);

    if ($storeWithSameName) {
      $response->getBody()->write(json_encode(
        ["message" => "Loja já cadastrada"]
      ));

      return $response->withStatus(409);
    }

    $attachment = $this->attachmentsRepository->findById($attachmentId);

    if (!$attachment) {
      $response->getBody()->write(json_encode(
        ["message" => "Midia não encontrada"]
      ));

      return $response->withStatus(404);
    }

    if ($attachment->userId !== $userId) {
      $response->getBody()->write(json_encode(
        ["message" => "Não autorizado"]
      ));

      return $response->withStatus(403);
    }

    $store = new Store(
      null,
      $name,
      $adress,
      $userId, 
      $attachment->id, 
      null
    );

    $this->storesRepository->create($store);

    return $response->withStatus(201);
  }
}
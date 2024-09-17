<?php

namespace App\Controllers;

use App\Database\Repositories\StoresRepository;
use App\Domain\Entities\Store;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateStoreController {
  private StoresRepository $storesRepository;

  public function __construct(StoresRepository $storesRepository) {
    $this->storesRepository = $storesRepository;
  }

  function handle(Request $request, Response $response) {
    $body = $request->getParsedBody();

    $name = $body['name'];
    $adress = $body['adress'];
    $userId = $request->getAttribute('userId');

    $storeWithSameName = $this->storesRepository->findByName($name);

    if ($storeWithSameName) {
      $response->getBody()->write(json_encode(
        ["message" => "Loja já cadastrada"]
      ));

      return $response->withStatus(409);
    }

    $store = new Store(null, $name, $adress, $userId);

    $this->storesRepository->create($store);

    return $response->withStatus(201);
  }
}
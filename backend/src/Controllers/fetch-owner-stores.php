<?php

namespace App\Controllers;

use App\Database\Repositories\StoresRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FetchOwnerStoresController {
  private StoresRepository $storesRepository;

  public function __construct(StoresRepository $storesRepository) {
    $this->storesRepository = $storesRepository;
  }

  function handle(Request $request, Response $response, array $args) {
    $userId = $request->getAttribute('userId');
   
    $stores = $this->storesRepository->findManyByOwnerId($userId);

    $response->getBody()->write(json_encode([
      "stores" => $stores
    ]));

    return $response->withStatus(200);
  }
}
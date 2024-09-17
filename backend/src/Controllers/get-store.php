<?php

namespace App\Controllers;

use App\Database\Repositories\StoresRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetStoreController {
  private StoresRepository $storesRepository;

  public function __construct(StoresRepository $storesRepository) {
    $this->storesRepository = $storesRepository;
  }

  function handle(Request $request, Response $response, array $args) {
    $storeId = $args['id'];
   
    $store = $this->storesRepository->findById($storeId);

    if (!$store) {
      $response->getBody()->write(json_encode(
        ["message" => "Loja não encontrada"]
      ));

      return $response->withStatus(404);
    }

    $response->getBody()->write(json_encode(["store" => $store]));

    return $response->withStatus(200);
  }
}
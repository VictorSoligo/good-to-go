<?php

namespace App\Controllers;

use App\Database\Repositories\OffersRepository;
use App\Database\Repositories\StoresRepository;
use App\Domain\Entities\Date;
use App\Domain\Entities\Offer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateOfferController {
  private OffersRepository $offersRepository;
  private StoresRepository $storesRepository;

  public function __construct(
    OffersRepository $offersRepository,
    StoresRepository $storesRepository
  ) {
    $this->offersRepository = $offersRepository;
    $this->storesRepository = $storesRepository;
  }

  function handle(Request $request, Response $response) {
    $userId = $request->getAttribute('userId');
    
    $body = $request->getParsedBody();

    $description = $body['description'];
    $availableUntil = $body['availableUntil'];
    $storeId = $body['storeId'];

    $store = $this->storesRepository->findById($storeId);

    if (!$store) {
      $response->getBody()->write(json_encode(
        ["message" => "Loja não encontrada"]
      ));

      return $response->withStatus(404);
    }

    if ($store->ownerId !== $userId) {
      $response->getBody()->write(json_encode(
        ["message" => "Não autorizado"]
      ));

      return $response->withStatus(403);
    }

    $offer = new Offer(
      null, 
      $storeId, 
      $description, 
      new Date($availableUntil), 
      null, 
      null
    );

    $this->offersRepository->create($offer);

    return $response->withStatus(201);
  }
}
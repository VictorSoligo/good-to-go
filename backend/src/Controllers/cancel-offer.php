<?php

namespace App\Controllers;

use App\Database\Repositories\OffersRepository;
use App\Database\Repositories\StoresRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CancelOfferController {
  private OffersRepository $offersRepository;
  private StoresRepository $storesRepository;

  public function __construct(
    OffersRepository $offersRepository,
    StoresRepository $storesRepository
  ) {
    $this->offersRepository = $offersRepository;
    $this->storesRepository = $storesRepository;
  }

  function handle(Request $request, Response $response, array $args) {
    $userId = $request->getAttribute('userId');
    $offerId = $args['id'];

    $offer = $this->offersRepository->findById($offerId);

    if (!$offer) {
      $response->getBody()->write(json_encode(
        ["message" => "Oferta não encontrada"]
      ));

      return $response->withStatus(404);
    }

    $store = $this->storesRepository->findById($offer->storeId);

    if ($store->ownerId !== $userId) {
      $response->getBody()->write(json_encode(
        ["message" => "Não autorizado"]
      ));

      return $response->withStatus(403);
    }

    $offer->cancel();

    $this->offersRepository->save($offer);

    return $response->withStatus(204);
  }
}
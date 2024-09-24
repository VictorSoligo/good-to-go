<?php

namespace App\Controllers;

use App\Database\Repositories\OffersRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetOfferController {
  private OffersRepository $offerRepository;

  public function __construct(OffersRepository $offerRepository) {
    $this->offerRepository = $offerRepository;
  }

  function handle(Request $request, Response $response, array $args) {
    $offerId = $args['id'];
   
    $offer = $this->offerRepository->findByIdWithDetails($offerId);

    if (!$offer) {
      $response->getBody()->write(json_encode(
        ["message" => "Oferta não encontrada"]
      ));

      return $response->withStatus(404);
    }

    $response->getBody()->write(json_encode(["offer" => $offer]));

    return $response->withStatus(200);
  }
}
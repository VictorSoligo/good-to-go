<?php

namespace App\Controllers;

use App\Database\Repositories\OffersRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FetchActiveOffersController {
  private OffersRepository $offersRepository;

  public function __construct(OffersRepository $offersRepository) {
    $this->offersRepository = $offersRepository;
  }

  function handle(Request $request, Response $response, array $args) {
    $offers = $this->offersRepository->findManyActive();

    $response->getBody()->write(json_encode([
      "offers" => $offers
    ]));

    return $response->withStatus(200);
  }
}
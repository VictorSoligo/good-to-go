<?php

namespace App\Di;

use App\Controllers\AuthenticateUserController;
use App\Controllers\CancelOfferController;
use App\Controllers\CreateOfferController;
use App\Controllers\CreateStoreController;
use App\Controllers\FetchActiveOffersController;
use App\Controllers\FetchOwnerStoresController;
use App\Controllers\GetStoreController;
use App\Controllers\GetUserProfileController;
use UMA\DIC\ServiceProvider;
use UMA\DIC\Container;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use App\Database\Repositories\UsersRepository;
use Slim\Middleware\ContentLengthMiddleware;
use Slim\Factory\AppFactory;
use App\Database\Connection;
use App\Controllers\RegisterUserController;
use App\Controllers\UploadAttachmentController;
use App\Database\Repositories\AttachmentsRepository;
use App\Database\Repositories\OffersRepository;
use App\Database\Repositories\StoresRepository;
use App\Domain\Entities\Attachment;

final class AppProvider implements ServiceProvider {
  public function provide(Container $c): void {
    $this->provideFrameworkServices($c);
    $this->provideRepositories($c);
    $this->provideControllers($c);
  }

  private function provideFrameworkServices(Container $c): void {
    $c->set(App::class, static function (ContainerInterface $c): App {
      $app = AppFactory::create(null, $c);
      $app->add(new ContentLengthMiddleware());
      $app->addBodyParsingMiddleware();
      $app->addRoutingMiddleware();
      $app->addErrorMiddleware(true, true, true);

      $app->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($app): ResponseInterface {
        if ($request->getMethod() === 'OPTIONS') {
          $response = $app->getResponseFactory()->createResponse();
        } else {
          $response = $handler->handle($request);
        }
    
        $response = $response
          ->withHeader('Access-Control-Allow-Credentials', 'true')
          ->withHeader('Access-Control-Allow-Origin', '*')
          ->withHeader('Access-Control-Allow-Headers', '*')
          ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
          ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
          ->withHeader('Pragma', 'no-cache');
    
        if (ob_get_contents()) {
          ob_clean();
        }
    
        return $response;
      });

      return $app;
    });
  }

  private function provideRepositories(Container $c): void {
    $c->set(UsersRepository::class, static function (): UsersRepository {
      $instance = Connection::getInstance();
      $mysql = $instance->getConnection();

      return new UsersRepository($mysql);
    });

    $c->set(StoresRepository::class, static function (): StoresRepository {
      $instance = Connection::getInstance();
      $mysql = $instance->getConnection();

      return new StoresRepository($mysql);
    });

    $c->set(OffersRepository::class, static function (): OffersRepository {
      $instance = Connection::getInstance();
      $mysql = $instance->getConnection();

      return new OffersRepository($mysql);
    });

    $c->set(AttachmentsRepository::class, static function (): AttachmentsRepository {
      $instance = Connection::getInstance();
      $mysql = $instance->getConnection();

      return new AttachmentsRepository($mysql);
    });
  }

  private function provideControllers(Container $c): void {
    $c->set(RegisterUserController::class, static function (ContainerInterface $c): RegisterUserController {
      return new RegisterUserController(
        $c->get(UsersRepository::class)
      );
    });

    $c->set(AuthenticateUserController::class, static function (ContainerInterface $c): AuthenticateUserController {
      return new AuthenticateUserController(
        $c->get(UsersRepository::class)
      );
    });

    $c->set(CreateStoreController::class, static function (ContainerInterface $c): CreateStoreController {
      return new CreateStoreController(
        $c->get(StoresRepository::class),
        $c->get(AttachmentsRepository::class),
      );
    });

    $c->set(GetStoreController::class, static function (ContainerInterface $c): GetStoreController {
      return new GetStoreController(
        $c->get(StoresRepository::class)
      );
    });

    $c->set(FetchOwnerStoresController::class, static function (ContainerInterface $c): FetchOwnerStoresController {
      return new FetchOwnerStoresController(
        $c->get(StoresRepository::class)
      );
    });

    $c->set(GetUserProfileController::class, static function (ContainerInterface $c): GetUserProfileController {
      return new GetUserProfileController(
        $c->get(UsersRepository::class)
      );
    });

    $c->set(CreateOfferController::class, static function (ContainerInterface $c): CreateOfferController {
      return new CreateOfferController(
        $c->get(OffersRepository::class),
        $c->get(StoresRepository::class),
      );
    });

    $c->set(CancelOfferController::class, static function (ContainerInterface $c): CancelOfferController {
      return new CancelOfferController(
        $c->get(OffersRepository::class),
        $c->get(StoresRepository::class),
      );
    });

    $c->set(FetchActiveOffersController::class, static function (ContainerInterface $c): FetchActiveOffersController {
      return new FetchActiveOffersController(
        $c->get(OffersRepository::class),
      );
    });

    $c->set(UploadAttachmentController::class, static function (ContainerInterface $c): UploadAttachmentController {
      return new UploadAttachmentController(
        $c->get(AttachmentsRepository::class),
      );
    });
  }
}
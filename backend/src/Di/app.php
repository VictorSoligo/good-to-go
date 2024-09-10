<?php

namespace App\Di;

use App\Controllers\AuthenticateUserController;
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
  }
}
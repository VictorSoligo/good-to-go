<?php

use Slim\App;
use UMA\DIC\Container;
use App\Di\AppProvider;

require __DIR__ . '/vendor/autoload.php';    
require __DIR__ . '/src/Env/index.php';

$container = new Container();

$container->register(new AppProvider());

$app = $container->get(App::class);

(require __DIR__ . '/src/routes.php')($app);

$app->run();

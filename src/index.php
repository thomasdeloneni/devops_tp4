<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

// Create Container
$container = new Container();
AppFactory::setContainer($container);

// Create App
$app = AppFactory::create();

// Add routes
$app->get('/', function (Request $request, Response $response) {
    $data = [
        'message' => 'Bienvenue dans l\'application de démonstration Docker!',
        'status' => 'success'
    ];
    
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/health', function (Request $request, Response $response) {
    $data = [
        'status' => 'healthy',
        'service' => 'demo-app'
    ];
    
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/info', function (Request $request, Response $response) {
    $data = [
        'hostname' => gethostname(),
        'php_version' => PHP_VERSION,
        'environment' => getenv('APP_ENV') ?: 'development',
        'system' => php_uname()
    ];
    
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
});

// Run app
$app->run();

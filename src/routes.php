<?php

require dirname(__DIR__).'/Controllers/DataController.php';

use Slim\Http\Request;
use Slim\Http\Response;
use App\Controllers\Music as Client;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});



// API group
$app->group('/api', function () use ($app) {
    
    // Version Group
    $app->group('/v1', function () use ($app) {
        
        //$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
        $app->get('/albums', function (Request $request, Response $response, array $args) {

            $data = new Client();
            $data->generateToken();
            $data->getArtistId($_GET['q']);
            $data->getAlbums();
        });
        
    });
        
});
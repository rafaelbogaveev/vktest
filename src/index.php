<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.10.2017
 * Time: 12:46
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require  '../vendor/autoload.php';

//$config = require '../prod.config.php'; //for production use
$config = require '../dev.config.php'; // for development use
$app = new \Slim\App($config);


$app->get('/', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello world ". $this->get('settings')['db']['host']);
    return $response;
});

$app->get('/by_price', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Sorted by price");

    return $response;
});


$app->run();
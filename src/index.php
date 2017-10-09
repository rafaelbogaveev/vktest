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
    require_once('db.php');
    //require_once ('memcached.php');

    $query='select * from products';
    $result = $mysqli->query($query);

    $data = null;
    while($row = $result->fetch_assoc()) {
        $data[]=$row;
    }


    $memcached = new Memcached();
    $memcached->addServer('localhost', 11211) or die ("Не могу подключиться к memcached");
    echo 'version:'. $memcached->getVersion();
    $memcached->set('key', $data) or die('Ошибка при сохранении данных');


    //echo json_encode($memcached->get('key'));
});

$app->post('add', function (Request $request, Response $response){

});


$app->put('/edit/', function (Request $request, Response $response) {

});

$app->delete('/delete/', function (Request $request, Response $response){

});


$app->run();
<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/11/17
 * Time: 1:49 PM
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/list', function (Request $request, Response $response) {
    $lastId=$request->getQueryParams()['lastId'];
    $limit=$request->getQueryParams()['limit'];
    $offset=$request->getQueryParams()['offset'];
    $orderField=$request->getQueryParams()['orderField'];
    $orderType=$request->getQueryParams()['orderType'];

    echo json_encode(getProducts($lastId, $limit, $offset, $orderField, $orderType));
});

$app->get('/product/{id}', function (Request $request, Response $response) {

});


$app->post('/add', function (Request $request, Response $response){

});


$app->put('/edit/{id}', function (Request $request, Response $response) {

});

$app->delete('/delete/{id}', function (Request $request, Response $response){

});

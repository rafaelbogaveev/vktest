<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/11/17
 * Time: 1:49 PM
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;



class ResponseDto
{
    public $products;

    public $lastId;

    public $total;
}


$app->get('/', function (Request $request, Response $response){
    $this->view->render($response, 'index.html', []);
});


$app->get('/api/list', function (Request $request, Response $response) {
    $lastId=$request->getQueryParams()['lastId'];
    $limit=$request->getQueryParams()['limit'];
    $offset=$request->getQueryParams()['offset'];
    $orderField=$request->getQueryParams()['orderField'];
    $orderType=$request->getQueryParams()['orderType'];

    $products = getProducts($lastId, $limit, $offset, $orderField, $orderType);
    $data = new ResponseDto();
    $data->products=$products;
    $data->lastId=3;
    $data->total=3;

    header("Content-Type: application/json");
    return json_encode($data);
});

$app->get('/api/product/{id}', function (Request $request, Response $response) {

});


$app->post('/api/add', function (Request $request, Response $response){
    $name=$request->getParam('name');
    $description=$request->getParam('description');
    $price=$request->getParam('price');
    $url=$request->getParam('url');

    save(null, $name, $description, $price, $url);

    return json_encode(array("status" => "200", "code" => 1));
});


$app->put('/api/edit/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $name=$request->getParam('name');
    $description=$request->getParam('description');
    $price=$request->getParam('price');
    $url=$request->getParam('url');

    save($id, $name, $description, $price, $url);

    return json_encode(array("status" => "200", "code" => 1));
});

$app->delete('/api/delete/{id}', function (Request $request, Response $response){
    $id = $request->getAttribute('id');
    delete($id);

    return json_encode(array("status" => "200", "code" => 1));
});

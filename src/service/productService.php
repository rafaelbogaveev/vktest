<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 10/10/17
 * Time: 12:41 PM
 *
 * This file contains implementation for crud operations over products
 */


require_once ('dbService.php');


/**
 * @param $lastId - last product id from previous page, null if first page requested. Used for sorted by id queries only
 * @param $limit - max amount of products that should be returned
 * @param $offset - amount of first items in query that should be omitted
 * @param $orderField - name of field that should be sorted. 'price' - sorting by price, otherwise-sorting by id
 * @param $orderType - type of sorting (asc, desc). 'desc' - sorting by desc, otherwise- sorting by asc
 */
function getProducts($limit, $offset, $orderField, $orderType){
    require (__DIR__ . '/../data/db.php');

    $order = $orderType=='desc' ? $orderType: 'asc';

    if ('price' == $orderField){
        return getProductsSortedByPrice($limit, $offset, $order);
    }

    return getProductsSortedById($limit, $offset, $order);
}

/**
 * @param $id
 * @param $name
 * @param $description
 * @param $price
 * @param $url
 */
function save($id, $name, $description, $price, $url){
    if (@$id == null) {
        insertProduct($name, $description, $price, $url);
    }
    else{
        updateProduct($id, $name,$description, $price, $url);
    }
}

function delete($id){
    if ($id == null)
        throw new Exception('Id cannot be null');

    deleteProduct($id);
}

function getCount(){
    return getProductsCount();
}
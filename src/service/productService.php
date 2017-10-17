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
require_once ('cacheService.php');

//key for storing total number of products in cache
define("count_key", 'total_count');


/**
 * @param $limit - max amount of products that should be returned
 * @param $offset - amount of first items in query that should be omitted
 * @param $orderField - name of field that should be sorted. 'price' - sorting by price, otherwise-sorting by id
 * @param $orderType - type of sorting (asc, desc). 'desc' - sorting by desc, otherwise- sorting by asc
 */
function getProducts($limit, $offset, $orderField, $orderType){
    require (__DIR__ . '/../data/db.php');

    $orderType = $orderType=='desc' ? $orderType: 'asc';
    $orderField = 'price' == $orderField ? $orderField : 'id';

    //form key by page requested parameters
    $key = getKeyForPage($orderField, $limit, $offset, $orderType);

    //get data from cache by key
    $products = getValueByKey($key, $orderType);

    //if data exists then return it
    if (null != $products  && 0<count($products))
        return $products;

    $products = 'price' == $orderField ? getProductsSortedByPrice($limit, $offset, $orderType)
                                       : getProductsSortedById($limit, $offset, $orderType);

    //save list of products to cache
    saveValueByKey($key, $products);

    return $products;
}

/**
 * @param $id
 * @param $name
 * @param $description
 * @param $price
 * @param $url
 */
function save($id, $name, $description, $price, $url, $limit, $offset, $orderField, $orderType){
    if (null == $id) {
        insertProduct($name, $description, $price, $url);
        $count = getValueByKey(count_key);
        saveValueByKey(count_key, $count+1);
    }
    else{
        updateProduct($id, $name, $description, $price, $url);
    }

    $orderType = $orderType=='desc' ? $orderType: 'asc';
    $orderField = 'price' == $orderField ? $orderField : 'id';

    //form key by page requested parameters
    $key = getKeyForPage($orderField, $limit, $offset, $orderType);

    //delete data for page from cache
    deleteByKey($key);
}

function delete($id, $limit, $offset, $orderField, $orderType){
    if (null ==  $id)
        throw new Exception('Id cannot be null');

    $orderType = $orderType=='desc' ? $orderType: 'asc';
    $orderField = 'price' == $orderField ? $orderField : 'id';

    //form key by page requested parameters
    $key = getKeyForPage($orderField, $limit, $offset, $orderType);

    deleteProduct($id);

    // we cannot be sure whether element with id exists
    deleteByKey(count_key);

    //delete data for page from cache. It needs to be refreshed.
    deleteByKey($key);
}

/**
 * Get total number of stored products
 *
 * @return mixed
 */
function getCount(){
    $count = getValueByKey(count_key);

    if (null != $count)
        return $count;

    $count = getProductsCount();

    // save to cache
    saveValueByKey(count_key, $count);

    return $count;
}

/**
 *
 */
function deleteSortedByPricePagesFromCache(){

}

/**
 *
 */
function deleteSortedByIdPagesFromCache(){

}
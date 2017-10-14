<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 10/10/17
 * Time: 12:41 PM
 *
 * This file contains implementation for crud operations over products
 */

require_once ('core/memcached.php');
require_once ('dbService.php');

global $config;
// maximum amount of items in block of products
$block_size = $config['settings']['block_size'];


/**
 * @param $lastId - last product id from previous page, null if first page requested. Used for sorted by id queries only
 * @param $limit - max amount of products that should be returned
 * @param $offset - amount of first items in query that should be omitted
 * @param $orderField - name of field that should be sorted. 'price' - sorting by price, otherwise-sorting by id
 * @param $orderType - type of sorting (asc, desc). 'desc' - sorting by desc, otherwise- sorting by asc
 */
function getProducts($lastId, $limit, $offset, $orderField, $orderType){

    updateProduct(1,'watermellon', 'superFruit', 6.7, null);
    insertProduct('apple', 'tasty fruit', 6.7, null);


    if ('price'== $orderField){
        return getProductsSortedByPrice($limit, $offset, $orderType);
    }

    return getProductsSortedById($lastId, $limit, $orderType);
}





function getItemsAndCached(){

}
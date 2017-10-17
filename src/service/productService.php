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

//key for storing prefix for sorted by price pages
define('price_prefix_key', 'price_prefix');

//key for storing prefix for sorted by id pages
define('id_prefix_desc_key', 'id_desc_prefix');

//key for storing prefix for sorted by id pages
define('id_prefix_asc_key', 'id_asc_prefix');

define('price_field', 'price');

define('id_field', 'id');

define('asc', 'asc');

define('desc', 'desc');



/**
 * @param $limit - max amount of products that should be returned
 * @param $offset - amount of first items in query that should be omitted
 * @param $orderField - name of field that should be sorted. 'price' - sorting by price, otherwise-sorting by id
 * @param $orderType - type of sorting (asc, desc). 'desc' - sorting by desc, otherwise- sorting by asc
 */
function getProducts($limit, $offset, $orderField, $orderType){
    require (__DIR__ . '/../data/db.php');

    $orderType = $orderType==desc ? $orderType: asc;
    $orderField = price_field == $orderField ? $orderField : id_field;

    //form key by page requested parameters
    $key = getKeyForPage($orderField, $limit, $offset, $orderType);

    //get data from cache by key
    $products = getValueByKey($key, $orderType);

    //if data exists then return it
    if (null != $products  && 0<count($products))
        return $products;

    $products = price_field == $orderField ? getProductsSortedByPrice($limit, $offset, $orderType)
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

        incTotalCount();
        refreshPages(price_field, asc);
        refreshPages(id_field, desc);
        refreshLastPageSortedByIdAsc($limit);
    }
    else{
        updateProduct($id, $name, $description, $price, $url);
        refreshPages(price_field, asc);
    }
}


/**
 * @param $id
 * @param $limit
 * @param $offset
 * @param $orderField
 * @param $orderType
 * @throws Exception
 */
function delete($id, $limit, $offset, $orderField, $orderType){
    if (null ==  $id)
        throw new Exception('Id cannot be null');

    deleteProduct($id);

    // we cannot be sure whether element with id exists
    deleteByKey(count_key);

    //refresh pages.
    refreshPages(id_field, asc);
    refreshPages(id_field, desc);
    refreshPages(price_field, asc);
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
function refreshPages($orderField, $orderType){
    changeKeyPrefix($orderField, $orderType);
}

function refreshLastPageSortedByIdAsc($limit)
{
    $count = getCount();
    $page = ceil($count / $limit);
    $offset = $limit * ($page - 1);
    $key = getKeyForPage(id_field, $limit, $offset,asc);
    deleteByKey($key);
}


function incTotalCount(){
    $count = getValueByKey(count_key);
    if (null != $count) {
        saveValueByKey(count_key, $count + 1);
    }
}


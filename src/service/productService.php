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
function getProducts($limit, $offset, $orderField, $orderType)
{
    require(__DIR__ . '/../data/db.php');

    $orderType = $orderType == desc ? $orderType : asc;
    $orderField = price_field == $orderField ? $orderField : id_field;

    //form key by requested parameters
    $key = getKeyForPage($orderField, $limit, $offset, $orderType);

    //get data from cache by key
    $products = getValueByKey($key, $orderType);

    //if data exists then return it
    if (null != $products && 0 < count($products)) {
        return $products;
    }

    $products = price_field == $orderField ? getProductsSortedByPrice($limit, $offset, $orderType)
        : getProductsSortedById($limit, $offset, $orderType);

    //save list of products to cache
    saveValueByKey($key, $products);

    //only for list sorted by id
    if (id_field == $orderField) {
        saveMapIdToOrder($products, $offset, $orderType);
    }

    return $products;
}

/**
 * Saves fields of product into database
 *
 * @param $id unuque identifier of product
 * @param $name name of product
 * @param $description  text, describing product's details
 * @param $price double number
 * @param $url link to image url
 */
function save($id, $name, $description, $price, $url, $limit){
    require (__DIR__ . '/../data/db.php');
    $mysqli->autocommit(FALSE);

    if (null == $id) {
        insertProduct($name, $description, $price, $url, $mysqli);

        deleteByKey(count_key);
        clearCachePages(price_field, asc);
        clearCachePages(id_field, desc);
        clearLastPageSortedByIdAsc($limit);
    }
    else{
        updateProduct($id, $name, $description, $price, $url, $mysqli);
        clearCachePages(price_field, asc);
        clearUpdatedProductPageSortedById($id, $limit);
    }

    $mysqli->commit();
}


/**
 *Deletes product from database by id
 *
 * @param $id - unique identifier of product
 * @throws Exception
 */
function delete($id){
    require (__DIR__ . '/../data/db.php');

    if (null ==  $id)
        throw new Exception('Id cannot be null');

    $mysqli->autocommit(FALSE);
    deleteProduct($id, $mysqli);

    deleteByKey(count_key);
    clearCachePages(price_field, asc);
    clearCachePages(id_field, desc);
    clearCachePages(id_field, asc);

    $mysqli->commit();
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
 * Save to cache relation of each product id to order in database
 *
 * @param $products list of products
 * @param $limit maximum amount of element in page, needs to calculate current page
 * @param $offset offset from first element in db, needs to calculate current page
 */
function saveMapIdToOrder($products, $offset, $orderType)
{
    $totalCount = getCount();

    foreach ($products as $index => $product) {
        // we need to save order of product in ascending type of sorting
        $value = desc == $orderType ? $totalCount - ($offset + $index) : $offset + $index+1;
        saveValueByKey($product['id'], $value);
    }
}


/**
 * Changes prefixes of keys for pages
 *
 * @param $orderField
 * @param $orderType
 */
function clearCachePages($orderField, $orderType){
    changeKeyPrefix($orderField, $orderType);
}

/**
 *
 *
 * @param $limit
 */
function clearLastPageSortedByIdAsc($limit)
{
    $count = getCount();
    $lastPage = ceil($count / $limit);
    $offset = $limit * ($lastPage - 1);
    $key = getKeyForPage(id_field, $limit, $offset,asc);
    deleteByKey($key);
}

/**
 * Clears pages for sorted by id list for specified product $id
 *
 * @param $id
 */
function clearUpdatedProductPageSortedById($id, $limit)
{
    global $app;

    // resolving logger
    $logger = $app->getContainer()->get('logger');

    $logger->info('------Clear pages for updates item------');

    //number of page for product with id in a list sorted by id field by asc
    $itemOrder = getValueByKey($id);

    if (null == $itemOrder)
        return;

    $totalCount = getCount();
    $logger->info('count:'.$totalCount);
    $logger->info('itemOrder:'.$itemOrder);

    //number of page for product with id in a list sorted by id field by desc
    $descItemOrder = $totalCount - $itemOrder + 1;
    $logger->info('descItemOrder:'.$descItemOrder);

    $offset = $limit * floor(($itemOrder-1)/$limit);
    $logger->info('offset asc:'.$offset);
    $key = getKeyForPage(id_field, $limit, $offset, asc);
    deleteByKey($key);

    $offset = $limit * floor(($descItemOrder-1)/$limit);
    $logger->info('offset desc:'.$offset);
    $key = getKeyForPage(id_field, $limit, $offset, desc);
    deleteByKey($key);
}

<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/14/17
 * Time: 5:49 AM
 */

require_once (__DIR__ . '/../data/memcached.php');

//key for storing prefix for sorted by price pages
define('price_prefix_key', 'price_prefix');

//key for storing prefix for sorted by id pages
define('id_prefix_desc_key', 'id_desc_prefix');


/**
 * Returns data stored by key formed by parameters
 *
 * @param $key
 * @return mixed
 */
function getValueByKey($key){
   require (__DIR__.'/../data/memcached.php');
   global $app;
   $logger = $app->getContainer()->get('logger');

   $logger->info("Get data from cache by key=".$key);

   return $memcached->get($key);
}

/**
 * @param $key
 * @param $data
 */
function saveValueByKey($key, $data){
    require (__DIR__.'/../data/memcached.php');
    global $app;
    $logger = $app->getContainer()->get('logger');

    $logger->info("Save data into cache for key=".$key);

    $memcached->set($key, $data);
}

/**
 * @param $key
 */
function deleteByKey($key){
    require (__DIR__.'/../data/memcached.php');
    global $app;
    $logger = $app->getContainer()->get('logger');

    $logger->info("Delete data from cache by key=".$key);

    $memcached->delete($key);
}


/**
 *
 * @param $orderField
 * @param $limit
 * @param $offset
 * @param $orderType
 * @return string
 */
function getKeyForPage($orderField, $limit, $offset, $orderType){
    $prefix = getKeyPrefix($orderField, $orderType);
    $key = $prefix.'_'.$limit.'_'.$offset.'_'.$orderType;

    return $key;
}


/**
 * @param $orderField
 * @param $orderType
 */
function getKeyPrefix($orderField, $orderType){
    require (__DIR__.'/../data/memcached.php');

    if ('price' == $orderField){
        $lastId = $memcached->get(price_prefix_key);
        if (null == $lastId){
            $lastId =1;
            saveValueByKey(price_prefix_key, $lastId);
        }

        return $orderField.$lastId;
    }

    if ('id' == $orderField && 'desc'==$orderType){
        $lastId = $memcached->get(id_prefix_desc_key);
        if (null == $lastId){
            $lastId =1;
            saveValueByKey(id_prefix_desc_key, $lastId);
        }

        return $orderField.$lastId;
    }

    return $orderField;
}

/**
 * @param $orderField
 */
function changeKeyPrefix($orderField)
{
    require(__DIR__ . '/../data/memcached.php');

    if ('price' == $orderField) {
        $lastId = $memcached->get(price_prefix_key);

        $lastId = null == $lastId ? 1 : $lastId + 1;
        saveValueByKey(price_prefix_key, $lastId);
    }

    if ('id' == $orderField) {
        $lastId = $memcached->get(id_prefix_desc_key);

        $lastId = null == $lastId ? 1 : $lastId + 1;
        saveValueByKey(id_prefix_desc_key, $lastId);
    }
}
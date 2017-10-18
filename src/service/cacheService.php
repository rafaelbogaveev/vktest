<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/14/17
 * Time: 5:49 AM
 */

require_once (__DIR__ . '/../data/memcached.php');



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

   $data = $memcached->get($key);
   $logger->info("Get data from cache by key=".$key.' value='.$data);

   return $data;
}

/**
 * @param $key
 * @param $data
 */
function saveValueByKey($key, $data){
    require (__DIR__.'/../data/memcached.php');
    global $app;
    $logger = $app->getContainer()->get('logger');

    $logger->info("Save data into cache for key=".$key.' value: '.$data);

    $memcached->set($key, $data);
}

/**
 * @param $key
 */
function deleteByKey($key){
    require (__DIR__.'/../data/memcached.php');
    global $app;
    $logger = $app->getContainer()->get('logger');

    $logger->info("Deleting data from cache by key=".$key);

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
    global $app;
    $logger = $app->getContainer()->get('logger');

    $prefix = getKeyPrefix($orderField, $orderType);
    $key = $prefix.'_'.$limit.'_'.$offset.'_'.$orderType;

    $logger->info('Key requested for '.$orderField.'_'.$orderType.': '.$key);

    return $key;
}


/**
 * @param $orderField
 * @param $orderType
 */
function getKeyPrefix($orderField, $orderType)
{
    require(__DIR__ . '/../data/memcached.php');

    global $app;
    $logger = $app->getContainer()->get('logger');
    $logger->info('Get prefix for '.$orderField.'_'.$orderType);

    //
    if (price_field == $orderField) {
        $lastId = $memcached->get(price_prefix_key);
        if (null == $lastId) {
            $lastId = 1;
            saveValueByKey(price_prefix_key, $lastId);
        }

        return $orderField . $lastId;
    }

    if (id_field == $orderField && desc == $orderType) {
        $lastId = $memcached->get(id_prefix_desc_key);
        if (null == $lastId) {
            $lastId = 1;
            saveValueByKey(id_prefix_desc_key, $lastId);
        }

        return $orderField . $lastId;
    }

    $lastId = $memcached->get(id_prefix_asc_key);
    if (null == $lastId) {
        $lastId = 1;
        saveValueByKey(id_prefix_asc_key, $lastId);
    }

    return $orderField . $lastId;
}

/**
 * Changes prefix for key that used to store pages in cache
 *
 * @param $orderField
 * @param $orderType
 */
function changeKeyPrefix($orderField, $orderType)
{
    require(__DIR__ . '/../data/memcached.php');
    global $app;
    $logger = $app->getContainer()->get('logger');
    $logger->info('Changing prefix for '.$orderField.'_'.$orderType);

    if (price_field == $orderField) {
        $lastId = $memcached->get(price_prefix_key);

        $lastId = null == $lastId ? 1 : $lastId + 1;
        saveValueByKey(price_prefix_key, $lastId);
        $logger->info('New prefix for '.price_prefix_key.': '.$lastId);

        return;
    }

    if (id_field == $orderField && desc == $orderType) {
        $lastId = $memcached->get(id_prefix_desc_key);

        $lastId = null == $lastId ? 1 : $lastId + 1;
        saveValueByKey(id_prefix_desc_key, $lastId);
        $logger->info('New prefix for '.id_prefix_desc_key.': '.$lastId);

        return;
    }

    if (id_field == $orderField && asc == $orderType) {
        $lastId = $memcached->get(id_prefix_asc_key);

        $lastId = null == $lastId ? 1 : $lastId + 1;
        saveValueByKey(id_prefix_asc_key, $lastId);
        $logger->info('New prefix for ' . id_prefix_asc_key . ': ' . $lastId);

        return;
    }
}


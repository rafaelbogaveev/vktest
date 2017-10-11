<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/11/17
 * Time: 12:24 PM
 */



/**
 *
 *
 * @param $lastId
 * @param $limit
 * @param $orderType
 * @return array of db rows
 */

function getProductsSortedById($lastId, $limit, $orderType){
    require_once ('core/db.php');

    global $config;
    global $app;
    // maximum amount of items in block of products
    $block_size = $config['settings']['block_size'];
    // resolving logger
    $logger = $app->getContainer()->get('logger');

    $order = $orderType=='desc' ? $orderType: 'asc';
    $whereClause = null==$lastId ? '' : 'where id>'.$lastId;

    $query = 'Select * From products '.$whereClause.' order by id '.$order.' limit '.$block_size;
    $result = $mysqli->query($query);

    $logger->info("Fetching list of products sorted by id from database. SQL: ".$query);

    $data=null;
    while($row = $result->fetch_assoc()) {
        $data[]=$row;
    }

    return $data;
}


/**
 * Gets array of products row sorted by price
 *
 * @param $limit - max amount of products that should be returned
 * @param $offset - amount of first items in query that should be omitted
 * @param $orderType - type of sorting (asc, desc). 'desc' - sorting by desc, otherwise- sorting by asc
 * @return array of db rows
 */
function getProductsSortedByPrice($limit, $offset, $orderType) {
    require_once ('core/db.php');

    $order = 'desc'==$orderType ? $orderType: 'asc';
    $result = $mysqli->query('Select * From products order by price '.$order.' limit '.$block_size.' offset '.$offset);

    $data=null;
    while($row = $result->fetch_assoc()) {
        $data[]=$row;
    }

    return $data;
}

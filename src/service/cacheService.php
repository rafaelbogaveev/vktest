<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/14/17
 * Time: 5:49 AM
 */

require_once (__DIR__ . '/../data/memcached.php');



/**
 * @param $limit
 * @param $offset
 * @param $orderField
 */
function getBlockSize($offset, $orderField)
{
    global $config;
    // maximum amount of items in block of products
    $block_size = $config['settings']['block_size'];

    // if sorting is not by descening or we are not fetching the last block
    //we need this part to avoid intersection of two blocks
    if ($orderField != 'desc' || $offset>$block_size) return $block_size;

    // for the last block  we need to get tail
    $count = getProductsCount();

    return $count % $block_size == 0 ? $block_size : $count % $block_size;
}

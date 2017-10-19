<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/11/17
 * Time: 12:24 PM
 */



/**
 * Gets list of products from database sorted by id field
 *
 * @param $limit - max amount of products that should be returned
 * @param $offset - amount of first items in query that should be omitted
 * @param $orderType - type of sorting (asc, desc). 'desc' - sorting by desc, otherwise- sorting by asc
 * @return array of db rows
 */
function getProductsSortedById($limit, $offset, $orderType){
    require (__DIR__.'/../data/db.php');
    global $app;

    $logger = $app->getContainer()->get('logger');

    $query = 'Select id, name, ifnull(description, \'\') as description, price, ifnull(url,\'\') as url From products order by id '.$orderType.' limit '.$limit.' offset '.$offset;

    $logger->info("Fetching list of products sorted by id from database. SQL: ".$query);
    $result = $mysqli->query($query);



    $data=null;
    while($row = $result->fetch_assoc()) {
        $data[]=$row;
    }

    $logger->info("Fetched list of products sorted by id from database. Count: ".count($data));

    return $data;
}


/**
 * Gets array of products row sorted by price field
 *
 * @param $limit - max amount of products that should be returned
 * @param $offset - amount of first items in query that should be omitted
 * @param $orderType - type of sorting (asc, desc). 'desc' - sorting by desc, otherwise- sorting by asc
 * @return array of db rows
 */
function getProductsSortedByPrice($limit, $offset, $orderType)
{
    require(__DIR__ . '/../data/db.php');
    global $app;

    // resolving logger
    $logger = $app->getContainer()->get('logger');


    $order = 'desc' == $orderType ? $orderType : 'asc';
    $query = 'SELECT id, name, ifnull(description, \'\') AS description, price, ifnull(url,\'\') AS url FROM products ORDER BY price ' . $order . ' LIMIT ' . $limit . ' OFFSET ' . $offset;

    $logger->info("Fetched list of products sorted by price from database. SQL: " . $query);

    $result = $mysqli->query($query);

    $data = null;
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $logger->info("Fetched list of products sorted by price from database. Count: " . count($data));

    return $data;
}


/**
 * Returns product by identifier
 *
 * @param $id - identifier of product
 */
function getProductById($id){
    require (__DIR__ . '/../data/db.php');
    global $app;

    $query = 'Select * From products where id='.$id;

    $logger = $app->getContainer()->get('logger');
    $logger->info("Get product by id. SQL: ".$query);

    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();

    return $row;
}


/**
 *
 *
 * @param $id
 * @param $name
 * @param $description
 * @param $price
 * @param $url
 * @return bool
 */
function updateProduct($id, $name, $description, $price, $url, $mysqli)
{
    global $app;

    $queryFormat = 'UPDATE products SET name=%s, description=%s, price=%s, url=%s WHERE id=%d';
    $query = sprintf($queryFormat,
        $name == null ? 'null' : '\'' . $name . '\'',
        $description == null ? 'null' : '\'' . $description . '\'',
        $price == null ? 'null' : $price,
        $url == null ? 'null' : '\'' . $url . '\'',
        $id);

    $logger = $app->getContainer()->get('logger');
    $logger->info("Update product. SQL: " . $query);

    return $mysqli->query($query) === TRUE;
}


/**
 *
 *
 * @param $name
 * @param $description
 * @param $price
 * @param $url
 */
function insertProduct($name, $description, $price, $url, $mysqli)
{
    global $app;

    $queryFormat = 'INSERT INTO products (name, description, price, url) VALUES (%s, %s, %s, %s)';
    $query = sprintf($queryFormat,
        $name == null ? 'null': '\''.$name.'\'',
        $description == null ? 'null': '\''.$description.'\'',
        $price == null ? 'null': $price,
        $url==null ? 'null': '\''.$url.'\'',
        $url==null ? 'null': $url);

    $logger = $app->getContainer()->get('logger');
    $logger->info("Insert product. SQL: " . $query);

    return $mysqli->query($query) === TRUE;
}


/**
 * @param $id
 * @return bool
 */
function deleteProduct($id, $mysqli){
    global $app;

    $query = 'Delete From products WHERE id='.$id;

    $logger = $app->getContainer()->get('logger');
    $logger->info("Insert product. SQL: " . $query);

    return $mysqli->query($query) === TRUE;
}


/**
 *
 */
function getProductsCount(){
    require (__DIR__.'/../data/db.php');
    global $app;

    $query = 'Select count(id) as count From products';
    $logger = $app->getContainer()->get('logger');
    $logger->info("Get products count. SQL: " . $query);

    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();
    $logger->info("count: " . $row['count']);

    return $row['count'];
}

<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.10.2017
 * Time: 15:07
 */
global $config;

$db = $config['settings']['db'];
$mysqli = new mysqli($db['host'], $db['user'], $db['password'], $db['dbname']) or die('Cannot connect to database');
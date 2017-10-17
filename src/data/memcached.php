<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 10/9/17
 * Time: 2:33 PM
 */
global $config;

$cache_settings = $config['settings']['memcached'];

$memcached = new Memcached;
$memcached->addServer($cache_settings['host'], $cache_settings['port']) or die ("Не могу подключиться к memcached");

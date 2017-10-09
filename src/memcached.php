<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 10/9/17
 * Time: 2:33 PM
 */
$memcache = new Memcache;
$memcache->connect('localhost', 11211) or die ("Не могу подключиться к memcached");


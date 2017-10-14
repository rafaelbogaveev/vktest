<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.10.2017
 * Time: 16:39
 */

$config = [
    'settings' => [
        'displayErrorDetails' => false,

        'db' => [
            'host' => 'localhost',
            'user' => 'root',
            'password' => 'root',
            'dbname' => 'stock'
        ],

        'memcached'=>[
            'host' => '127.0.0.1',
            'port' => 11211,
        ],

        'logger' => [
            'name' => 'vk-test',
            'level' => Monolog\Logger::DEBUG,
            'path' => __DIR__ . '/../../logs/app.log',
        ],


        'page_size' => 30,
    ]
];
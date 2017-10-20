<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.10.2017
 * Time: 16:39
 */

$config = [
    'settings' => [
        'displayErrorDetails' => true,

        'db' => [
            'host' => 'app_db',
            'user' => 'root',
            'password' => '1234',
            'dbname' => 'stock'
        ],

        'memcached'=>[
            'host' => 'memcached',
            'port' => 11211,
        ],

        'logger' => [
            'name' => 'vk-test',
            'level' => Monolog\Logger::DEBUG,
            'path' => __DIR__ . '/../../logs/app.log',
        ]
    ]
];

return $config;
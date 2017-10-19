<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.10.2017
 * Time: 12:46
 */


require  '../vendor/autoload.php';
require_once ('service/productService.php');

$config = require __DIR__. '/config/prod.config.php'; //for production use
//$config = require __DIR__. '/config/dev.config.php'; // for development use

$app = new \Slim\App($config);

//registering dependencies
require __DIR__. '/config/dependencies.php';

require __DIR__ . '/routes/routes.php';

$app->run();
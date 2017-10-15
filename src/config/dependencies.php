<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/11/17
 * Time: 1:43 PM
 */

$container = $app->getContainer();

$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__.'/../templates', [
        'cache' => __DIR__.'/../templates/cache'
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.html', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$container['errorHandler'] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        //Format of exception to return
        $data = [
            'message' => $exception->getMessage()
        ];
        return $container->get('response')->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($data));
    };
};


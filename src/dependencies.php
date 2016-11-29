<?php
// DIC configuration
use Slim\Views\JadeRenderer;

$container = $app->getContainer();

// view renderer
$container['renderer'] = new JadeRenderer(  __DIR__.'/../views' );
// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};


$container["jwt"] = function ($container) {
	return new StdClass;
};


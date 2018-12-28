<?php

require 'vendor/autoload.php';

use App\Router;
use React\Http\Server;
use Psr\Http\Message\ServerRequestInterface;

$loop = React\EventLoop\Factory::create();

$router = new Router($loop);
$router->load(__DIR__ . '/routes.php');

$server = new Server(
    function (ServerRequestInterface $request) use ($router) {
        return $router($request);
    }
);

$socket = new React\Socket\Server(8080, $loop);

$server->listen($socket);

echo 'Работает на '
    . str_replace('tcp:', 'http:', $socket->getAddress())
    . PHP_EOL;

$loop->run();

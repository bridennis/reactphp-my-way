<?php

require 'vendor/autoload.php';

use App\ErrorHandler;
use App\Router;
use React\Http\Server;
use Psr\Http\Message\ServerRequestInterface;

$loop = React\EventLoop\Factory::create();

$router = new Router($loop);
$router->load(__DIR__ . '/routes.php');

$errorHandler = new ErrorHandler($loop);

$server = new Server(
    function (ServerRequestInterface $request) use ($router, $errorHandler) {
        try {
            return $router($request);
        } catch (Throwable $exception) {
            return $errorHandler->handle($exception);
        }
    }
);

$socket = new React\Socket\Server(8080, $loop);
$server->listen($socket);

$server->on('error', function (Exception $exception) {
    echo 'Error: ' . $exception->getMessage() . PHP_EOL;
    if ($exception->getPrevious() !== null) {
        echo 'Error: ' . $exception->getPrevious()->getMessage() . PHP_EOL;
    }
});

echo 'Работает на '
    . str_replace('tcp:', 'http:', $socket->getAddress())
    . PHP_EOL;

$loop->run();

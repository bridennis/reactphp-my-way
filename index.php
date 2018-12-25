<?php

require 'vendor/autoload.php';

use React\Http\Server;
use React\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

$loop = React\EventLoop\Factory::create();

$server = new Server(
        function (ServerRequestInterface $request) {
            $browser = implode($request->getHeader('User-Agent'));
            echo 'Браузер: ' . $browser . PHP_EOL;

            $params = $request->getQueryParams();
            $name = $params['name'] ?? 'мир';

            return new Response(
                200,
                ['Content-Type' => 'text/plain; charset=UTF-8'],
                'Привет, ' . $name
            );
        }
    );

$socket = new React\Socket\Server(8080, $loop);

$server->listen($socket);

echo 'Работает на '
    . str_replace('tcp:', 'http:', $socket->getAddress())
    . PHP_EOL;

$loop->run();

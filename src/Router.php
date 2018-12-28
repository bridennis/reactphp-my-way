<?php

namespace app;

use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\LoopInterface;
use React\Http\Response;

class Router
{
    private $routes = [];

    /** @var LoopInterface */
    private $loop;

    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $path = $request->getUri()->getPath();
        echo 'Запрос: ' . $path . PHP_EOL;

        $handler = $this->routes[$path] ?? $this->notFound($path);

        return $handler($request, $this->loop);
    }

    private function notFound($path)
    {
        return function () use ($path) {
            return new Response(
                404,
                ['Content-type' => 'text/plain; charset=UTF-8'],
                'Нет обработчика запроса для ' . $path
            );
        };
    }

    public function load($filename)
    {
        $routes = require $filename;
        foreach ($routes as $path => $handler) {
            $this->add($path, $handler);
        }
    }

    public function add($path, callable $handler)
    {
            $this->routes[$path] = $handler;
    }
}

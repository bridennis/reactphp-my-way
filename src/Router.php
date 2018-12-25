<?php

class Router
{
    private $routes = [];

    public function __invoke($path)
    {
        if (!isset($this->routes[$path])) {
            echo 'Нет обработчика запроса для ' . $path . PHP_EOL;
            return;
        }
        echo 'Запрос: ' . $path . PHP_EOL;
        $handler = $this->routes[$path];
        $handler();
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

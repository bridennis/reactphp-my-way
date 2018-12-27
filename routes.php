<?php

use Psr\Http\Message\ServerRequestInterface;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use React\Http\Response;

return [
    '/' => function (
        ServerRequestInterface $request,
        LoopInterface $loop
    ) {
        $childProcess = new Process('cat pages/index.html', __DIR__);
        $childProcess->start($loop);

        return new Response(
            200,
            ['Content-type' => 'text/html; charset=UTF-8'],
            $childProcess->stdout
        );
    },

    '/upload' => function (ServerRequestInterface $request) {
        return new Response(
            200,
            ['Content-type' => 'text/plain; charset=UTF-8'],
            'Страница загрузки'
        );
    }
];

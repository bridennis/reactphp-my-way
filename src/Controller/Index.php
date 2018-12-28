<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use React\Http\Response;

class Index
{
    public function __invoke(
        ServerRequestInterface $request,
        LoopInterface $loop
    ) {
        $listFiles = new Process('ls uploads', __DIR__ . '/../..');
        $listFiles->start($loop);

        $renderPage = new Process('php pages/index.php', __DIR__ . '/../..');
        $renderPage->start($loop);

        $listFiles->stdout->pipe($renderPage->stdin);

        return new Response(
            200,
            ['Content-Type' => 'text/html; charset=UTF-8'],
            $renderPage->stdout
        );
    }
}

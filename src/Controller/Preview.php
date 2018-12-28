<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use React\Http\Response;

class Preview
{
    public function __invoke(
        ServerRequestInterface $request,
        LoopInterface $loop
    )
    {
        $fileName = trim($request->getUri()->getPath(), '/');
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $readFile = new Process("cat $fileName", __DIR__ . '/../..');
        $readFile->start($loop);
        return new Response(
            200,
            ['Content-Type' => 'image/' . $ext],
            $readFile->stdout
        );
    }
}

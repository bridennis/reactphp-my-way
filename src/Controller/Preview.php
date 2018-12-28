<?php

namespace App\Controller;

use App\ChildProcessFactory;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\LoopInterface;
use React\Http\Response;

class Preview
{
    private $childProcesses;

    public function __construct(ChildProcessFactory $childProcesses)
    {
        $this->childProcesses = $childProcesses;
    }

    public function __invoke(
        ServerRequestInterface $request,
        LoopInterface $loop
    ) {
        $fileName = trim($request->getUri()->getPath(), '/');
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $readFile = $this->childProcesses->create('cat ' . $fileName);

        $readFile->start($loop);

        return new Response(
            200,
            ['Content-Type' => 'image/' . $ext],
            $readFile->stdout
        );
    }
}

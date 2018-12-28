<?php

namespace App\Controller;

use App\ChildProcessFactory;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\LoopInterface;
use React\Http\Response;

class Download
{
    private $childProcesses;

    public function __construct(ChildProcessFactory $childProcesses)
    {
        $this->childProcesses = $childProcesses;
    }

    public function __invoke(ServerRequestInterface $request, LoopInterface $loop)
    {
        $fileName = str_replace(
            'download/',
            '',
            trim($request->getUri()->getPath(), '/')
        );
        $readFile = $this->childProcesses->create('cat ' . $fileName);

        $readFile->start($loop);

        return new Response(
            200,
            ['Content-Disposition' => 'attachment'],
            $readFile->stdout
        );
    }
}

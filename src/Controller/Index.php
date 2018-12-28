<?php

namespace App\Controller;

use App\ChildProcessFactory;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\LoopInterface;
use React\Http\Response;

class Index
{
    private $childProcesses;

    public function __construct(ChildProcessFactory $childProcesses)
    {
        $this->childProcesses = $childProcesses;
    }

    public function __invoke(ServerRequestInterface $request, LoopInterface $loop)
    {
        $listFiles = $this->childProcesses->create('ls uploads');
        $listFiles->start($loop);

        $renderPage = $this->childProcesses->create('php pages/index.php');
        $renderPage->start($loop);

        $listFiles->stdout->pipe($renderPage->stdin);

        return new Response(
            200,
            ['Content-Type' => 'text/html; charset=UTF-8'],
            $renderPage->stdout
        );
    }
}

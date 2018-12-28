<?php

namespace App\Controller;

use App\ChildProcessFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use React\EventLoop\LoopInterface;
use React\Http\Response;

class Upload
{
    private $childProcesses;

    public function __construct(ChildProcessFactory $childProcesses)
    {
        $this->childProcesses = $childProcesses;
    }

    public function __invoke(
        ServerRequestInterface $request,
        LoopInterface $loop
    )
    {
        /** @var UploadedFileInterface $file */
        $file = $request->getUploadedFiles()['file'];
        $process = $this->childProcesses->create('cat > uploads/' . $file->getClientFilename());

        $process->start($loop);

        $process->stdin->end($file->getStream()->getContents());

        return new Response(302, ['Location' => '/']);

    }
}

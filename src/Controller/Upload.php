<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use React\Http\Response;

class Upload
{
    public function __invoke(
        ServerRequestInterface $request,
        LoopInterface $loop
    )
    {
        /** @var UploadedFileInterface $file */
        $file = $request->getUploadedFiles()['file'];
        $process = new Process(
            'cat > uploads/' . $file->getClientFilename(),
            __DIR__ . '/../..'
        );
        $process->start($loop);
        $process->stdin->end($file->getStream()->getContents());

        return new Response(302, ['Location' => '/']);

    }
}

<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
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

    '/upload' => function (
        ServerRequestInterface $request,
        LoopInterface $loop
    ) {
        /** @var UploadedFileInterface $file */
        $file = $request->getUploadedFiles()['file'];
        $process = new Process(
            'cat > uploads/' . $file->getClientFilename(),
            __DIR__
        );
        $process->start($loop);
        $process->stdin->write($file->getStream()->getContents());

        return new Response(
            200,
            ['Content-Type' => 'text/plain; charset=UTF-8'],
            'Загрузка завершена'
        );
    }
];

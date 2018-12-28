<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use React\Http\Response;

return [
    '/uploads/.*\.(jpg|png)$' => function (
        ServerRequestInterface $request,
        LoopInterface $loop
    ) {
        $fileName = trim($request->getUri()->getPath(), '/');
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $readFile = new Process("cat $fileName", __DIR__);
        $readFile->start($loop);
        return new Response(
            200,
            ['Content-Type' => 'image/' . $ext],
            $readFile->stdout
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
        $process->stdin->end($file->getStream()->getContents());

        return new Response(302, ['Location' => '/']);
    },

    '/' => function (
        ServerRequestInterface $request,
        LoopInterface $loop
    ) {
        $listFiles = new Process('ls uploads', __DIR__);
        $listFiles->start($loop);

        $renderPage = new Process('php pages/index.php', __DIR__);
        $renderPage->start($loop);

        $listFiles->stdout->pipe($renderPage->stdin);

        return new Response(
            200,
            ['Content-Type' => 'text/html; charset=UTF-8'],
            $renderPage->stdout
        );
    }
];

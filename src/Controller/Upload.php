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

    public function __invoke(ServerRequestInterface $request, LoopInterface $loop)
    {
        /** @var UploadedFileInterface $file */
        $file = $request->getUploadedFiles()['file'];
        $fileName = $file->getClientFilename();
        $saveUpload = $this->childProcesses->create(
            'cat > uploads/' . $fileName
        );
        $saveUpload->start($loop);
        $saveUpload->stdin->end(
            $file->getStream()->getContents()
        );

        $saveUpload->stdin->on(
            'close',
            function () use ($fileName, $loop) {
                $this->createPreview($fileName, $loop);
            }
        );

        return new Response(302, ['Location' => '/']);
    }

    private function createPreview($fileName, LoopInterface $loop)
    {
        $createPreview = $this->childProcesses->create(
            'convert uploads/' . $fileName . ' -resize 128x128 previews/' . $fileName
        );
        $createPreview->start($loop);
    }
}

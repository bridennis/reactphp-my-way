<?php

use App\ChildProcessFactory;
use App\Controller\Download;
use App\Controller\Index;
use App\Controller\Preview;
use App\Controller\Upload;

$childProcessFactory = new ChildProcessFactory(__DIR__);

return [
    '/download/uploads/.*\.(jpg|png)$' => new Download($childProcessFactory),

    '/uploads/.*\.(jpg|png)$' => new Preview($childProcessFactory),

    '/upload' => new Upload($childProcessFactory),

    '/' => new Index($childProcessFactory)
];

<?php

use App\ChildProcessFactory;
use App\Controller\Index;
use App\Controller\Preview;
use App\Controller\Upload;

$childProcessFactory = new ChildProcessFactory(__DIR__);

return [
    '/uploads/.*\.(jpg|png)$' => new Preview($childProcessFactory),

    '/upload' => new Upload($childProcessFactory),

    '/' => new Index($childProcessFactory)
];

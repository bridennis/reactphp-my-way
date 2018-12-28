<?php

use App\Controller\Index;
use App\Controller\Preview;
use App\Controller\Upload;

return [
    '/uploads/.*\.(jpg|png)$' => new Preview(),

    '/upload' => new Upload(),

    '/' => new Index()
];

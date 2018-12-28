<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Response;

class Favicon
{
    public function __invoke(ServerRequestInterface $request)
    {
        return new Response(200, ['Content-Type' => 'image/x-icon']);
    }
}

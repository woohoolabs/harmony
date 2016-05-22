<?php
namespace WoohooLabs\Harmony\Examples\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetBookAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response->getBody()->write("This is a book with ID '" . $request->getAttribute("id") . "'");

        return $response;
    }
}

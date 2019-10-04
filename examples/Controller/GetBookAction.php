<?php

declare(strict_types=1);

namespace WoohooLabs\Harmony\Examples\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetBookAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $bookId = $request->getAttribute("id");

        $response->getBody()->write("This is a book with ID '$bookId'.");

        return $response;
    }
}

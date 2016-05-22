<?php
namespace WoohooLabs\Harmony\Examples\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserController
{
    public function getUser(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response->getBody()->write("This is a user with ID '" . $request->getAttribute("id") . "'!");

        return $response;
    }
}

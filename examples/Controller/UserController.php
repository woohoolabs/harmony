<?php
namespace WoohooLabs\Harmony\Examples\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController
{
    public function getUser(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response->getBody()->write("This is a user with ID '" . $request->getAttribute("id") . "'!");

        return $response;
    }
}

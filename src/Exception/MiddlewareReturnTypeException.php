<?php
namespace WoohooLabs\Harmony\Exception;

use Exception;

class MiddlewareReturnTypeException extends Exception
{
    public function __construct()
    {
        parent::__construct("A middleware must return a \\Psr\\Http\\Message\\ResponseInterface instance!");
    }
}

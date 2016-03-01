<?php
namespace WoohooLabs\Harmony\Exception;

class MiddlewareReturnTypeException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Middlewares must return a \\Psr\\Http\\Message\\ResponseInterface instance!");
    }
}

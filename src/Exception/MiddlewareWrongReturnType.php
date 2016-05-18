<?php
namespace WoohooLabs\Harmony\Exception;

use Exception;

class MiddlewareWrongReturnType extends Exception
{
    public function __construct()
    {
        parent::__construct("A middleware must return a \\Psr\\Http\\Message\\ResponseInterface instance!");
    }
}

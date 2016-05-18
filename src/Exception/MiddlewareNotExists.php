<?php
namespace WoohooLabs\Harmony\Exception;

use Exception;

class MiddlewareNotExists extends Exception
{
    protected $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct("A middleware with '" . $this->name . "' ID does not exist!");
    }
}

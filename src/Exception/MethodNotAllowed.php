<?php
namespace WoohooLabs\Harmony\Exception;

use Exception;

class MethodNotAllowed extends Exception
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @param string $method
     */
    public function __construct($method)
    {
        $this->method = $method;
        parent::__construct("Method '" . $method . "' is not allowed!");
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
}

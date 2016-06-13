<?php
namespace WoohooLabs\Harmony\Exception;

use Exception;

class MiddlewareNotExists extends Exception
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        parent::__construct("A middleware with '" . $name . "' ID does not exist!");
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

<?php
namespace WoohooLabs\Harmony\Exception;

use Exception;

class RouteNotFound extends Exception
{
    /**
     * @var string
     */
    protected $route;

    public function __construct($route)
    {
        $this->route = $route;
        parent::__construct("Route '" . $route . "' can not be found!");
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }
}

<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Exception;

use Exception;

class RouteNotFound extends Exception
{
    /**
     * @var string
     */
    protected $route;

    public function __construct(string $route)
    {
        $this->route = $route;
        parent::__construct("Route '" . $route . "' can not be found!");
    }

    public function getRoute(): string
    {
        return $this->route;
    }
}

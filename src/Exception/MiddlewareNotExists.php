<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Exception;

use Exception;

class MiddlewareNotExists extends Exception
{
    /**
     * @var string
     */
    protected $name;

    public function __construct(string $name)
    {
        $this->name = $name;
        parent::__construct("A middleware with '" . $name . "' ID does not exist!");
    }

    public function getName(): string
    {
        return $this->name;
    }
}

<?php
declare(strict_types=1);

namespace WoohooLabs\Harmony\Exception;

use Exception;

class MethodNotAllowed extends Exception
{
    /**
     * @var string
     */
    protected $method;

    public function __construct(string $method)
    {
        $this->method = $method;
        parent::__construct("Method '" . $method . "' is not allowed!");
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}

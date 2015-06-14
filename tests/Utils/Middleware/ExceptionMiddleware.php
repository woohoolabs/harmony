<?php
namespace WoohooLabsTest\Harmony\Utils\Middleware;

use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\MiddlewareInterface;
use WoohooLabsTest\Harmony\Utils\Exception\TestException;

class ExceptionMiddleware implements MiddlewareInterface
{
    protected $id;

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \WoohooLabs\Harmony\Harmony $harmony
     * @throws \WoohooLabsTest\Harmony\Utils\Exception\TestException
     */
    public function execute(Harmony $harmony)
    {
        throw new TestException($this->id);
    }
}

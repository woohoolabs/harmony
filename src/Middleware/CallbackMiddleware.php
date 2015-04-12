<?php
namespace WoohooLabs\Harmony\Middleware;

use WoohooLabs\Harmony\Harmony;

class CallbackMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @param string $id
     * @param callable $callback
     */
    public function __construct($id, callable $callback)
    {
        $this->id = $id;
        $this->callback = $callback;
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
     */
    public function execute(Harmony $harmony)
    {
        call_user_func($this->callback, $harmony);
    }
}

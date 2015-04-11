<?php
namespace WoohooLabs\Harmony\Event;

interface EventDispatcherConsumerInterface
{
    /**
     * @param string $eventName
     * @param string $className
     * @param string $methodName
     */
    public function addClassListener($eventName, $className, $methodName);

    /**
     * @param string $eventName
     * @param callable $listener
     */
    public function addCallbackListener($eventName, callable $listener);
}

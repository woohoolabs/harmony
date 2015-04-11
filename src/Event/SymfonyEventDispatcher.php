<?php
namespace WoohooLabs\Harmony\Event;

use Interop\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use WoohooLabs\Harmony\Request\RequestInterface;
use WoohooLabs\Harmony\Response\ResponseInterface;

class SymfonyEventDispatcher implements EventDispatcherInterface, EventDispatcherConsumerInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * @param \Interop\Container\ContainerInterface $container
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(ContainerInterface $container, EventDispatcher $eventDispatcher = null)
    {
        $this->container = $container;
        $this->eventDispatcher= $eventDispatcher === null ? new EventDispatcher() : $eventDispatcher;
    }

    /**
     * @param string $eventName
     * @param string $className
     * @param string $methodName
     */
    public function addClassListener($eventName, $className, $methodName)
    {
        $this->eventDispatcher->addListener($eventName, function(SymfonyEvent $event) use ($className, $methodName) {
            $object= $this->container->get($className);
            $object->{$methodName}($event);
        });
    }

    /**
     * @param string $eventName
     * @param callable $listener
     */
    public function addCallbackListener($eventName, callable $listener)
    {
        $this->eventDispatcher->addListener($eventName, $listener);
    }

    public function dispatchBeforeReceivingRequest()
    {
        $this->dispatch(Events::BEFORE_RECEIVING_REQUEST, new SymfonyEvent());
    }

    /**
     * @param \WoohooLabs\Harmony\Request\RequestInterface $request
     * @param \WoohooLabs\Harmony\Response\ResponseInterface $response
     */
    public function dispatchAfterReceivingRequest(RequestInterface $request, ResponseInterface $response)
    {
        if ($this->eventDispatcher->hasListeners(Events::AFTER_RECEIVING_REQUEST)) {
            $this->dispatch(Events::AFTER_RECEIVING_REQUEST, new SymfonyEvent($request, $response));
        }
    }

    /**
     * @param \WoohooLabs\Harmony\Request\RequestInterface $request
     * @param \WoohooLabs\Harmony\Response\ResponseInterface $response
     */
    public function dispatchAfterDiscovery(RequestInterface $request, ResponseInterface $response)
    {
        if ($this->eventDispatcher->hasListeners(Events::AFTER_DISCOVERY)) {
            $this->dispatch(Events::AFTER_DISCOVERY, new SymfonyEvent($request, $response));
        }
    }

    /**
     * @param \WoohooLabs\Harmony\Request\RequestInterface $request
     * @param \WoohooLabs\Harmony\Response\ResponseInterface $response
     */
    public function dispatchAfterRouting(RequestInterface $request, ResponseInterface $response)
    {
        if ($this->eventDispatcher->hasListeners(Events::AFTER_ROUTING)) {
            $this->dispatch(Events::AFTER_ROUTING, new SymfonyEvent($request, $response));
        }
    }

    /**
     * @param \WoohooLabs\Harmony\Request\RequestInterface $request
     * @param \WoohooLabs\Harmony\Response\ResponseInterface $response
     */
    public function dispatchBeforeSendingResponse(RequestInterface $request, ResponseInterface $response = null)
    {
        if ($this->eventDispatcher->hasListeners(Events::BEFORE_SENDING_RESPONSE)) {
            $this->dispatch(Events::BEFORE_SENDING_RESPONSE, new SymfonyEvent($request, $response));
        }
    }

    /**
     * @param string $eventName
     * @param \WoohooLabs\Harmony\Event\SymfonyEvent $event
     */
    protected function dispatch($eventName, SymfonyEvent $event = null)
    {
        $this->eventDispatcher->dispatch($eventName, $event);
    }
}

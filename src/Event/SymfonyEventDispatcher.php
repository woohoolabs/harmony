<?php
namespace WoohooLabs\ApiFramework\Event;

use Interop\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use WoohooLabs\ApiFramework\Request\RequestInterface;
use WoohooLabs\ApiFramework\Response\ResponseInterface;

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
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->eventDispatcher= new EventDispatcher();
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
     * @param \WoohooLabs\ApiFramework\Request\RequestInterface $request
     */
    public function dispatchAfterReceivingRequest(RequestInterface $request)
    {
        if ($this->eventDispatcher->hasListeners(Events::AFTER_RECEIVING_REQUEST)) {
            $this->dispatch(Events::AFTER_RECEIVING_REQUEST, new SymfonyEvent($request));
        }
    }

    /**
     * @param \WoohooLabs\ApiFramework\Request\RequestInterface $request
     */
    public function dispatchAfterDiscovery(RequestInterface $request)
    {
        if ($this->eventDispatcher->hasListeners(Events::AFTER_DISCOVERY)) {
            $this->dispatch(Events::AFTER_DISCOVERY, new SymfonyEvent($request));
        }
    }

    /**
     * @param \WoohooLabs\ApiFramework\Request\RequestInterface $request
     */
    public function dispatchAfterRouting(RequestInterface $request)
    {
        if ($this->eventDispatcher->hasListeners(Events::AFTER_ROUTING)) {
            $this->dispatch(Events::AFTER_ROUTING, new SymfonyEvent($request));
        }
    }

    /**
     * @param \WoohooLabs\ApiFramework\Request\RequestInterface $request
     * @param \WoohooLabs\ApiFramework\Response\ResponseInterface $response
     */
    public function dispatchBeforeSendingResponse(RequestInterface $request, ResponseInterface $response)
    {
        if ($this->eventDispatcher->hasListeners(Events::BEFORE_SENDING_RESPONSE)) {
            $this->dispatch(Events::BEFORE_SENDING_RESPONSE, new SymfonyEvent($request, $response));
        }
    }

    /**
     * @param string $eventName
     * @param \WoohooLabs\ApiFramework\Event\SymfonyEvent $event
     */
    protected function dispatch($eventName, SymfonyEvent $event = null)
    {
        $this->eventDispatcher->dispatch($eventName, $event);
    }
}

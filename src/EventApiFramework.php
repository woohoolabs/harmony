<?php
namespace WoohooLabs\ApiFramework;

use WoohooLabs\ApiFramework\Event\EventDispatcherInterface;
use WoohooLabs\ApiFramework\Event\SymfonyEventDispatcher;

class EventApiFramework extends ApiFramework
{
    /**
     * @var \WoohooLabs\ApiFramework\Event\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param \WoohooLabs\ApiFramework\EventConfig $config
     */
    public function __construct(EventConfig $config)
    {
        $this->config = $config;
    }

    protected function initializeTopComponents()
    {
        if ($this->eventDispatcher === null) {
            $this->eventDispatcher= new SymfonyEventDispatcher($this->container);
        }

        if ($this->config->getEvents() !== null) {
            call_user_func($this->config->getEvents(), $this->eventDispatcher);
        }

        $this->eventDispatcher->dispatchBeforeReceivingRequest();

        parent::initializeTopComponents();

        $this->eventDispatcher->dispatchAfterReceivingRequest($this->request);
    }

    protected function discover()
    {
        parent::discover();
        $this->eventDispatcher->dispatchAfterDiscovery($this->request);
    }

    protected function route()
    {
        parent::route();
        $this->eventDispatcher->dispatchAfterRouting($this->request);
    }

    protected function respond()
    {
        $this->eventDispatcher->dispatchBeforeSendingResponse($this->request, $this->response);
        parent::respond();
        $this->eventDispatcher->dispatchAfterSendingResponse();
    }

    /**
     * @param \WoohooLabs\ApiFramework\Event\EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }
}

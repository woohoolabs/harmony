<?php
namespace WoohooLabs\Harmony;

class EventConfig extends Config
{
    /**
     * @var \Closure|null
     */
    protected $events;

    /**
     * @return \Closure|null
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param \Closure $events
     */
    public function setEvents(\Closure $events)
    {
        $this->events = $events;
    }
}

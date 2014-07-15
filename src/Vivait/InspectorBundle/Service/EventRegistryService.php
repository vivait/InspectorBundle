<?php

namespace Vivait\InspectorBundle\Service;

class EventRegistryService
{
    private $events;

    /**
     * @param array $events
     */
    function __construct(array $events = [])
    {
        $this->events = $events;
    }

    /**
     * @param string $label
     * @param string $event
     * @todo This needs to store what each event provides
     */
    public function addEvent($label, $event)
    {
        if (isset($this->events[$event])) {
            throw new \InvalidArgumentException(sprintf('Event %s already exists', $event));
        }

        $this->events[$event] = $label;
    }

    /**
     * @return array
     */
    public function getList()
    {
        return $this->events;
    }

    /**
     * Gets events
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }

    public function hasEvent($event) {
        return (isset($this->events[$event]));
    }
} 
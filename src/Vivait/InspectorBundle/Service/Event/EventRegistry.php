<?php

namespace Vivait\InspectorBundle\Service\Event;

/**
 * Contains a register of all compatible events
 */
class EventRegistry
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
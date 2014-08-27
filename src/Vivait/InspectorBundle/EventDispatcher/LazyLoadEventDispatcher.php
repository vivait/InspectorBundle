<?php

namespace Vivait\InspectorBundle\EventDispatcher;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vivait\InspectorBundle\Model\InspectionProviderInterface;
use Vivait\InspectorBundle\Service\Inspection\RegisterInspection;

class LazyLoadEventDispatcher implements EventDispatcherInterface
{

    /**
     * @var ContainerAwareEventDispatcher
     */
    private $dispatcher;

    /**
     * Constructor.
     *
     * If an EventDispatcherInterface is not provided , a new EventDispatcher
     * will be composed.
     *
     * @param ContainerAwareEventDispatcher|EventDispatcherInterface $dispatcher
     * @param InspectionProviderInterface                            $provider
     * @param RegisterInspection                                     $registerService
     */
    public function __construct(EventDispatcherInterface $dispatcher = null, InspectionProviderInterface $provider, RegisterInspection $registerService)
    {
        $this->dispatcher = $dispatcher ?: new EventDispatcher();
        $this->registerInspections($provider, $registerService);
    }

    /**
     * Dispatches an event to all registered listeners.
     *
     * @param string $eventName The name of the event to dispatch. The name of
     *                          the event is the name of the method that is
     *                          invoked on listeners.
     * @param Event  $event     The event to pass to the event handlers/listeners.
     *                          If not supplied, an empty Event instance is created.
     *
     * @return Event
     *
     * @api
     */
    public function dispatch($eventName, Event $event = null)
    {
        return $this->dispatcher->dispatch($eventName, $event);
    }

    /**
     * Adds an event listener that listens on the specified events.
     *
     * @param string   $eventName The event to listen on
     * @param callable $listener  The listener
     * @param int      $priority  The higher this value, the earlier an event
     *                            listener will be triggered in the chain (defaults to 0)
     *
     * @api
     */
    public function addListener($eventName, $listener, $priority = 0)
    {
        $this->dispatcher->addListener($eventName, $listener, $priority);
    }

    /**
     * Adds an event subscriber.
     *
     * The subscriber is asked for all the events he is
     * interested in and added as a listener for these events.
     *
     * @param EventSubscriberInterface $subscriber The subscriber.
     *
     * @api
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->dispatcher->addSubscriber($subscriber);
    }

    /**
     * Removes an event listener from the specified events.
     *
     * @param string|array $eventName The event(s) to remove a listener from
     * @param callable     $listener  The listener to remove
     */
    public function removeListener($eventName, $listener)
    {
        $this->dispatcher->removeListener($eventName, $listener);
    }

    /**
     * Removes an event subscriber.
     *
     * @param EventSubscriberInterface $subscriber The subscriber
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->dispatcher->removeSubscriber($subscriber);
    }

    /**
     * Gets the listeners of a specific event or all listeners.
     *
     * @param string $eventName The name of the event
     *
     * @return array The event listeners for the specified event, or all event listeners by event name
     */
    public function getListeners($eventName = null)
    {
        return $this->dispatcher->getListeners($eventName);
    }

    /**
     * Checks whether an event has any registered listeners.
     *
     * @param string $eventName The name of the event
     *
     * @return bool    true if the specified event has any listeners, false otherwise
     */
    public function hasListeners($eventName = null)
    {
        return $this->dispatcher->hasListeners($eventName);
    }

    /**
     * Adds a service as event listener
     *
     * @param string $eventName   Event for which the listener is added
     * @param array  $callback    The service ID of the listener service & the method
     *                            name that has to be called
     * @param int    $priority    The higher this value, the earlier an event listener
     *                            will be triggered in the chain.
     *                            Defaults to 0.
     *
     * @throws \InvalidArgumentException
     */
    public function addListenerService($eventName, $callback, $priority = 0)
    {
        return $this->dispatcher->addListenerService($eventName, $callback, $priority);
    }

    /**
     * Adds a service as event subscriber
     *
     * @param string $serviceId The service ID of the subscriber service
     * @param string $class     The service's class name (which must implement EventSubscriberInterface)
     */
    public function addSubscriberService($serviceId, $class)
    {
        return $this->dispatcher->addSubscriberService($serviceId, $class);
    }

    public function getContainer()
    {
        return $this->dispatcher->getContainer();
    }

    // Proxy any other methods
    function __call($method, $args)
    {
        return call_user_func_array([$this->dispatcher, $method], $args);
    }

    /**
     * @param InspectionProviderInterface $provider
     * @param \Vivait\InspectorBundle\Service\Inspection\RegisterInspection             $registerService
     */
    private function registerInspections(InspectionProviderInterface $provider, RegisterInspection $registerService)
    {
        $registerService->setDispatcher($this->dispatcher);
        foreach ($provider->getInspections() as $inspection) {
            $registerService->registerInspection($inspection->getEventName(), $inspection->getId(), $inspection->getName());
        }
    }
}

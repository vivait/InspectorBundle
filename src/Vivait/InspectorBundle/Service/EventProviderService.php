<?php

namespace Vivait\InspectorBundle\Service;

use Metadata\MetadataFactoryInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Resource\DirectoryResource;
use Vivait\Voter\Model\EntityEvent;

class EventProviderService
{
    /**
     * @var ConfigCache
     */
    private $configCache;

    /**
     * @var string[]
     */
    private $eventLocations;

    function __construct(ConfigCache $configCache, array $eventLocations)
    {
        $this->configCache = $configCache;
        $this->eventLocations = $eventLocations;
    }

    public function getEventsFromCache(){
        if (!$this->configCache->isFresh()) {
            $events = $resources = [];
            foreach ($this->eventLocations as $namespace => $location) {
                $resources[] = new DirectoryResource($location);
                $events += $this->getEvents($namespace, $location);
            }

            $this->configCache->write('<?php return unserialize('.var_export(serialize($events), true).');', $resources);
        }

        $events = include (string)$this->configCache;

        return $events;
    }

    protected function locateEventClasses($eventNamespace, $eventPath) {
        $finder = new \Metadata\Driver\FileLocator([
            $eventNamespace => $eventPath
          ]);
        return $finder->findAllClasses('php');
    }

    public function getEvents($eventNamespace, $eventPath) {
        return $this->filterEntityEvents($this->locateEventClasses($eventNamespace, $eventPath));
    }

    /**
     * @param EntityEvent[] $classes
     * @return array
     */
    protected function filterEntityEvents(array $classes) {
        $events = [];
        foreach ($classes as $class) {
            if (is_a($class, 'Vivait\Voter\Model\EntityEvent', true)) {
                $events += $class::supportsEvents();
            }
        }

        return $events;
    }
}
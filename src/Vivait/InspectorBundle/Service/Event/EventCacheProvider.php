<?php

namespace Vivait\InspectorBundle\Service\Event;

use Metadata\MetadataFactoryInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Resource\DirectoryResource;
use Vivait\Voter\Model\EntityEvent;

/**
 * Scans the register locations for compatible event classes using the cache first
 */
class EventCacheProvider extends EventProvider
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

    public function getEvents(){
        // Rebuild a new cache
        if (!$this->configCache->isFresh()) {
            $resources = [];
            foreach ($this->eventLocations as $namespace => $location) {
                $resources[] = new DirectoryResource($location);
            }

            $events = parent::getEvents();

            $this->configCache->write('<?php return unserialize('.var_export(serialize($events), true).');', $resources);
        }
        else {
            $events = include (string)$this->configCache;
        }

        return $events;
    }
}
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
    protected $configCache;

    /**
     * @var string[]
     */
    protected $eventLocations;

    function __construct(array $eventLocations, ConfigCache $configCache)
    {
        $this->eventLocations = $eventLocations;
        $this->configCache = $configCache;
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
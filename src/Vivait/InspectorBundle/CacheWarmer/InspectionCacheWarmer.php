<?php

namespace Vivait\InspectorBundle\CacheWarmer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Resource\DirectoryResource;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;
use Vivait\InspectorBundle\Entity\Inspection;
use Vivait\InspectorBundle\Entity\InspectionRepository;

class InspectionCacheWarmer extends CacheWarmer
{
    /**
     * @var InspectionRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    private $cacheFile = 'appInspections.php';

    function __construct(EntityManagerInterface $entityManager, InspectionRepository $repository = null)
    {
        $this->repository = $repository ?: $entityManager->getRepository('VivaitInspectorBundle:Inspection');
        $this->entityManager = $entityManager;
    }

    /**
     * Warms up the cache.
     *
     * @param string $cacheDir The cache directory
     * @return array
     */
    public function warmUp($cacheDir)
    {
        $inspections = $this->repository->fetchInspections();
        $map = [];

        foreach ($inspections as $inspection) {
            $map[$inspection->getEventName()][$inspection->getId()] = $inspection->getName();
        }

        $this->writeCacheFile(
          $cacheDir . DIRECTORY_SEPARATOR . $this->cacheFile,
          sprintf('<?php return %s;', var_export($map, true))
        );
    }

    /**
     * Checks whether this warmer is optional or not.
     *
     * Optional warmers can be ignored on certain conditions.
     *
     * A warmer should return true if the cache can be
     * generated incrementally and on-demand.
     *
     * @return Boolean true if the warmer is optional, false otherwise
     */
    public function isOptional()
    {
        return false;
    }

    /**
     * Gets cacheFile
     * @return string
     */
    public function getCacheFile()
    {
        return $this->cacheFile;
    }

    /**
     * Sets cacheFile
     * @param string $cacheFile
     * @return $this
     */
    public function setCacheFile($cacheFile)
    {
        $this->cacheFile = $cacheFile;

        return $this;
    }
}
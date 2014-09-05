<?php

namespace Vivait\InspectorBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Vivait\InspectorBundle\Model\InspectionProviderInterface;

class InspectionRepository extends EntityRepository implements InspectionProviderInterface
{
    const RESULT_CACHE_LENGTH = 60;

    public function save(Inspection $event)
    {
        $em = $this->getEntityManager();
        $em->persist($event);
        $em->flush();
    }

    public function delete(Inspection $event, $flush = false)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($event);

        if ($flush) {
            $entityManager->flush();
        }
    }

    /**
     * @return Inspection[]
     */
    public function fetchInspections()
    {
        $query = $this->_em->createQueryBuilder()
          ->select('i, c, a')
          ->from($this->_entityName, 'i', 'i.id')
          ->leftJoin('i.conditions', 'c')
          ->leftJoin('i.actions', 'a')
          ->getQuery();

        return $query->getResult();
    }

    /**
     * @return array[int]
     */
    public function lazyFetchInspections()
    {
        $query = $this->_em->createQueryBuilder()
          ->select('i.id, i.name, i.eventName')
          ->from($this->_entityName, 'i', 'i.id')
          ->getQuery()
          ->useResultCache(true, self::RESULT_CACHE_LENGTH);

        return $query->getArrayResult();
    }

    public function getInspections($lazy = true)
    {
        return ($lazy) ? $this->lazyFetchInspections() : $this->fetchInspections();
    }
}

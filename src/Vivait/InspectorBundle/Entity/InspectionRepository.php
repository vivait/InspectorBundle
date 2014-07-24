<?php

namespace Vivait\InspectorBundle\Entity;

use Doctrine\ORM\EntityRepository;

class InspectionRepository extends EntityRepository
{
    public function save(Inspection $event) {
        $em = $this->getEntityManager();
        $em->persist($event);
        $em->flush();
    }

    public function delete(Inspection $event, $flush = false) {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($event);

        if ($flush) {
            $entityManager->flush();
        }
    }

    /**
     * @return Inspection[]
     */
    public function fetchInspections() {
        $query = $this->createQueryBuilder('i')
          ->select('i, c, a')
          ->leftJoin('i.conditions', 'c')
          ->leftJoin('i.actions', 'a')
          ->getQuery();

        return $query->getResult();
    }
}

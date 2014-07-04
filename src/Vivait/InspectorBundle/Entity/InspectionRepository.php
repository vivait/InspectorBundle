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

    public function delete(Inspection $event) {
        $this->getEntityManager()->remove($event);
    }
}

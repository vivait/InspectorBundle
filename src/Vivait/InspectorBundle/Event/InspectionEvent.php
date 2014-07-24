<?php

namespace Vivait\InspectorBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Vivait\Common\Event\EntityEvent;

class InspectionEvent extends EntityEvent {

    /**
     * @var \Vivait\InspectorBundle\Entity\Inspection
     */
    protected $entity;

    /**
     * Gets inspection
     * @return \Vivait\InspectorBundle\Entity\Inspection
     */
    public function getInspection()
    {
        return $this->entity;
    }

    /**
     * Sets inspection
     * @param \Vivait\InspectorBundle\Entity\Inspection $inspection
     * @return $this
     */
    public function setInspection($inspection)
    {
        $this->entity = $inspection;

        return $this;
    }

    public static function getEntityTypeLabel()
    {
        return 'inspection';
    }
}
<?php

namespace Vivait\InspectorBundle\Model;

use Vivait\InspectorBundle\Entity\Inspection;

interface InspectionProviderInterface {
    /**
     * @return Inspection[]
     */
    public function getInspections();
} 
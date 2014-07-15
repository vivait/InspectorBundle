<?php

namespace Vivait\InspectorBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class EntityEvent {
    public $label;
    public $provides = [];

    function __construct(array $data)
    {
        $this->label = $data['label'];
        $this->provides = $data['provides'];
    }


}
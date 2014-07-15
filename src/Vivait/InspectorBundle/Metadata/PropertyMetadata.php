<?php

namespace Vivait\InspectorBundle\Metadata;

use Metadata\PropertyMetadata as BasePropertyMetadata;

class PropertyMetadata extends BasePropertyMetadata {
    public $label;
    public $provides = [];

    public function serialize()
    {
        return serialize(array(
            $this->class,
            $this->name,
            $this->label,
            $this->provides
          ));
    }

    public function unserialize($str)
    {
        list($this->class, $this->name, $this->entityEvent) = unserialize($str);

        $this->reflection = new \ReflectionProperty($this->class, $this->name);
        $this->reflection->setAccessible(true);
    }
} 
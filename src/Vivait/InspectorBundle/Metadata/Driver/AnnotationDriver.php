<?php

namespace Vivait\InspectorBundle\Metadata\Driver;

use Metadata\Driver\DriverInterface;
use Metadata\MergeableClassMetadata;
use Doctrine\Common\Annotations\Reader;
use Vivait\InspectorBundle\Metadata\PropertyMetadata;

class AnnotationDriver implements DriverInterface
{
    /**
     * @var Reader
     */
    private $reader;


    public function __construct(Reader $annotation_reader)
    {
        $this->reader = $annotation_reader;
    }

    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $classMetadata = new MergeableClassMetadata($class->getName());


        foreach ($class->getConstants() as $key => $value) {
            $propertyMetadata = new PropertyMetadata($class->getName(), $key);

            var_dump($key);

            $annotation = $this->reader->getPropertyAnnotation(
              $reflectionProperty,
              'Vivait\\InspectionBundle\\Annotation\\EntityEvent'
            );

            var_dump($annotation);
            if (null !== $annotation) {
                $propertyMetadata->entityEvent = $annotation->value;
            }

            $classMetadata->addPropertyMetadata($propertyMetadata);
        }

        return $classMetadata;
    }
}
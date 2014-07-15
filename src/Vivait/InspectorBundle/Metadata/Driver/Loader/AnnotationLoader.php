<?php

namespace Vivait\InspectorBundle\Metadata\Driver\Loader;

use Symfony\Component\Config\Loader\FileLoader;

class AnnotationLoader extends FileLoader
{
    public function load($resource, $type = null)
    {
        var_dump($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'php' === pathinfo($resource, PATHINFO_EXTENSION) && (!$type || 'annotation' === $type);
    }
}
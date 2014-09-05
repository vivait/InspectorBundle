<?php
/*
 * Based upon SoclozEventQueueBundle
 * Copyright CloseToMe 2011/2012
 * Released under the The MIT License
 */

namespace Vivait\InspectorBundle\Serializer;

/**
 * Mandango serializer
 *
 * @author jfb
 */
class Mandango implements SerializerInterface
{
    protected $mandango;

    public function __construct($mandango)
    {
        $this->mandango = $mandango;
    }

    public function serialize($entities)
    {
        $entityData = $data = [];
        foreach ($entities as $key => $entity) {
            if (is_object($entity) && method_exists($entity, 'getId')) {
                $entityData[$key] = [get_class($entity), $entity->getId()];
            } else if (is_scalar($entity)) {
                $data[$key] = $entity;
            }
            else if (is_array($entity)) {
                $data[$key] = $this->serialize($entity);
            }
        }

        return [
          'entities' => $entityData,
          'data'     => $data
        ];
    }

    public function deserialize($data)
    {
        if (!is_array($data)) {
            return null;
        }

        $entities = [];

        foreach ($data['entities'] as $key => $value) {
            list($class, $id) = $value;
            $entities[$key] = $this->mandango->getRepository($class)->findOneById($id);
        }

        foreach ($data['data'] as $key => $value) {
            if (is_array($value)) {
                $entities[$key] = $this->deserialize($value);
            }
            else {
                $entities[$key] = $value;
            }
        }

        return $entities;
    }
}
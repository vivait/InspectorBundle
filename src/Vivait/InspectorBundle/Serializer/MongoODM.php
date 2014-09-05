<?php
/*
 * Based upon SoclozEventQueueBundle
 * Copyright CloseToMe 2011/2012
 * Released under the The MIT License
 */

namespace Vivait\InspectorBundle\Serializer;

/**
 * MongoDB ODM serializer
 *
 * @author jfb
 */
class MongoODM implements SerializerInterface
{
    protected $dm;

    public function __construct($dm)
    {
        $this->dm = $dm;
    }

    public function serialize($entities)
    {
        $data = $entityData = [];
        foreach ($entities as $key => $entity) {
            if (is_object($entity) && method_exists($entity, 'getId')) {
                $entityData[$key] = [get_class($entity), $entity->getId()];
            } else if (is_scalar($entity)) {
                $data[$key] = $entity;
            } else if (is_array($entity)) {
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
            $entities[$key] = $this->dm->find($class, new \MongoId($id));
        }

        foreach ($data['data'] as $key => $value) {
            if (is_array($value)) {
                $entities[$key] = $this->deserialize($value);
            } else {
                $entities[$key] = $value;
            }
        }

        return $entities;
    }
}
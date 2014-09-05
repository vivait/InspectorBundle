<?php
namespace Vivait\InspectorBundle\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;

/**
 * DoctrineORM serializer
 */
class DoctrineORM implements SerializerInterface
{
    protected $entity_manager;

    public function __construct(EntityManagerInterface $entity_manager)
    {
        $this->entity_manager = $entity_manager;
    }

    public function serialize($entities)
    {
        /* @var UnitOfWork $uow */
        $uow = $this->entity_manager->getUnitOfWork();
        $entityData = $data = [];
        foreach ($entities as $key => $entity) {
            if (is_object($entity) && $this->entity_manager->contains($entity)) {
                $id = $uow->getSingleIdentifierValue($entity);
                $entityData[$key] = [get_class($entity), $id];
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
            $entities[$key] = $this->entity_manager->getRepository($class)->find($id);
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
<?php

namespace Vivait\InspectorBundle\Condition;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Vivait\InspectorBundle\Entity\Inspection;
use Vivait\InspectorBundle\Queue\QueueInterface;
use Vivait\InspectorBundle\Serializer\SerializerInterface;
use Vivait\Voter\Model\ActionInterface;
use Vivait\Voter\Model\ConditionInterface;

class Queue implements ConditionInterface
{
    /**
     * @var QueueInterface
     */
    protected $queue;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var Inspection
     */
    protected $inspection;

    public function __construct(QueueInterface $queue, SerializerInterface $serializer)
    {
        $this->queue = $queue;
        $this->serializer = $serializer;
    }

    public function requires()
    {
        return [];
    }

    /**
     * @param mixed $entities
     * @return boolean
     */
    public function result($entities)
    {
        $data = $this->serializer->serialize($entities);
        $this->queue->put($this->inspection, $data);

        return false;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Queue inspection';
    }

    /**
     * Gets inspection
     * @return Inspection
     */
    public function getInspection()
    {
        return $this->inspection;
    }

    /**
     * Sets inspection
     * @param Inspection $inspection
     * @return $this
     */
    public function setInspection(Inspection $inspection)
    {
        $this->inspection = $inspection;

        return $this;
    }
}

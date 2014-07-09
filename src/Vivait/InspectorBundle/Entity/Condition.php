<?php

namespace Vivait\InspectorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Vivait\Common\Container\ServiceEntity;
use Vivait\InspectorBundle\Form\ActionType;

/**
 * @ORM\MappedSuperclass
 * @ORM\Table(name="InspectorCondition")
 * @ORM\Entity(repositoryClass="Vivait\InspectorBundle\Entity\ConditionRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 */
abstract class Condition implements ServiceEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="Vivait\InspectorBundle\Entity\Inspection", inversedBy="conditions")
     */
    private $inspection;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set inspection
     *
     * @param Inspection $inspection
     * @param bool $cascade
     * @return Condition
     */
    public function setInspection(Inspection $inspection = null, $cascade = true)
    {
        if ($cascade) {
            $inspection->addCondition($this, false);
        }

        $this->inspection = $inspection;

        return $this;
    }

    /**
     * Get inspection
     *
     * @return Inspection
     */
    public function getInspection()
    {
        return $this->inspection;
    }

    /**
     * @return ActionType
     */
    public abstract function getFormType();

}

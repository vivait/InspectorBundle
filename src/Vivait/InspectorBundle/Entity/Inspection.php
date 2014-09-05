<?php

namespace Vivait\InspectorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vivait\Voter\Model\VoterInterface;
use Vivait\Voter\Voter\AndVoter;
use Vivait\Voter\Voter\OrVoter;

/**
 * Inspection
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Vivait\InspectorBundle\Entity\InspectionRepository")
 */
class Inspection
{
    const VOTER_TYPE_AND = 1;
    const VOTER_TYPE_OR  = 2;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="eventName", type="string")
     */
    private $eventName;

    /**
     * @var string
     *
     * @ORM\Column(name="voterType", type="string")
     */
    private $voterType;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Vivait\InspectorBundle\Entity\Condition", mappedBy="inspection", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $conditions;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Vivait\InspectorBundle\Entity\Action", mappedBy="inspection", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $actions;

    /**
     * Constructor
     * @param null  $name
     * @param null  $eventName
     * @param int   $voterType
     * @param array $conditions
     * @param array $actions
     */
    public function __construct($name = null, $eventName = null, $voterType = self::VOTER_TYPE_AND, array $conditions = [], array $actions = [])
    {
        $this->name = $name;
        $this->eventName = $eventName;
        $this->voterType = $voterType;
        $this->conditions = new ArrayCollection($conditions);
        $this->actions    = new ArrayCollection($actions);
    }

    /**
     * Sets id
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * Set voterType
     *
     * @param string $voterType
     * @return Inspection
     */
    public function setVoterType($voterType)
    {
        $this->voterType = $voterType;

        return $this;
    }

    /**
     * Get voterType
     *
     * @return string
     */
    public function getVoterType()
    {
        return $this->voterType;
    }

    /**
     * Add conditions
     *
     * @param Condition $condition
     * @param bool $cascade
     * @return Inspection
     */
    public function addCondition(Condition $condition, $cascade = true)
    {
        if ($cascade){
            $condition->setInspection($this, false);
        }

        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * Remove conditions
     *
     * @param Condition $conditions
     */
    public function removeCondition(Condition $conditions)
    {
        $this->conditions->removeElement($conditions);
    }

    /**
     * Get conditions
     *
     * @return Collection|Condition[]
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * Add actions
     *
     * @param Action $action
     * @param bool $cascade
     * @return Inspection
     */
    public function addAction(Action $action, $cascade = true)
    {
        if ($cascade){
            $action->setInspection($this, false);
        }

        $this->actions[] = $action;

        return $this;
    }

    /**
     * Remove actions
     *
     * @param Action $actions
     */
    public function removeAction(Action $actions)
    {
        $this->actions->removeElement($actions);
    }

    /**
     * Get actions
     *
     * @return Collection|Action[]
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Set eventName
     *
     * @param string $eventName
     * @return Inspection
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;

        return $this;
    }

    /**
     * Get eventName
     *
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * Gets name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets name
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}

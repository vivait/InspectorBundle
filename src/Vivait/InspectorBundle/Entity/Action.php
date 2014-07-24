<?php

namespace Vivait\InspectorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Vivait\Common\Container\ServiceEntity;
use Vivait\InspectorBundle\Form\ActionType;
use Vivait\Voter\Model\ActionInterface;

/**
 * Action
 * @ORM\Table(name="InspectorAction")
 * @ORM\Entity(repositoryClass="Vivait\InspectorBundle\Entity\ActionRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 */
abstract class Action implements ServiceEntity
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
     * @ORM\ManyToOne(targetEntity="Vivait\InspectorBundle\Entity\Inspection", inversedBy="actions")
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
     * @return Action
     */
    public function setInspection(Inspection $inspection = null, $cascade = true)
    {
        if ($cascade) {
            $inspection->addAction($this, false);
        }

        $this->inspection = $inspection;

        return $this;
    }

    /**
     * Get inspection
     *
     * @return Inspection
     */
    private function getInspection()
    {
        return $this->inspection;
    }

    /**
     * @return ActionType
     */
    public abstract function getFormType();
}

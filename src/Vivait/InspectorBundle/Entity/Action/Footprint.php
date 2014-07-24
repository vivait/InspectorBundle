<?php

namespace Vivait\InspectorBundle\Entity\Action;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Vivait\Common\Model\Task\LetterInterface;
use Vivait\FootprintBundle\Entity\CannedMessage;
use Vivait\InspectorBundle\Entity\Action;
use Vivait\InspectorBundle\Form\ActionType;
use Vivait\InspectorBundle\Service\Action\FootprintService;

/**
 * Footprint
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Footprint extends Action
{
    /**
     * @ORM\ManyToOne(targetEntity="Vivait\FootprintBundle\Entity\CannedMessage", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $canned;

    public function loadService(ContainerInterface $container)
    {
        /* @var FootprintService $footprint */
        $footprint = $container->get('vivait_inspector.action.footprint');
        $footprint->setCannedMessage($this->getCanned());

        return $footprint;
    }

    /**
     * Gets canned
     * @return CannedMessage
     */
    public function getCanned()
    {
        return $this->canned;
    }

    /**
     * Sets canned
     * @param CannedMessage $canned
     * @return $this
     */
    public function setCanned($canned)
    {
        $this->canned = $canned;

        return $this;
    }

    /**
     * @return ActionType
     */
    public function getFormType()
    {
        return 'vivait_inspectorbundle_action_footprint';
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize(
          [
              $this->getId()
          ]
        );
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        list(
          $this->canned
          ) = unserialize($serialized);
    }
}

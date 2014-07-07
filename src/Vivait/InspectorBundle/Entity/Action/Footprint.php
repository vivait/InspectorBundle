<?php

namespace Vivait\InspectorBundle\Entity\Action;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Vivait\Common\Model\Task\LetterInterface;
use Vivait\FootprintBundle\Entity\CannedMessage;
use Vivait\InspectorBundle\Entity\Action;
use Vivait\InspectorBundle\Service\Action\FootprintService;

/**
 * Footprint
 *
 * @todo This should really use a canned message and just show a picklist
 * @ORM\Table()
 * @ORM\Entity()
 */
class Footprint extends Action
{
    /**
     * @ORM\OneToOne(targetEntity="Vivait\FootprintBundle\Entity\CannedMessage", cascade={"persist", "remove"})
     */
    private $canned;

    public function loadService(ContainerInterface $container) {
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
}

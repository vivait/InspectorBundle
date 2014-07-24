<?php

namespace Vivait\InspectorBundle\Service\Action;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Vivait\FootprintBundle\Entity\CannedMessage;
use Vivait\FootprintBundle\Service\MessageService;
use Vivait\InspectorBundle\Entity\Action;
use Vivait\Voter\Model\ActionInterface;

class FootprintService implements ActionInterface
{
    /**
     * @var CannedMessage
     */
    private $cannedMessage;

    /**
     * @var MessageService
     */
    private $messageService;

    function __construct(MessageService $messageService, CannedMessage $cannedMessage = null)
    {
        $this->messageService = $messageService;
    }

    public function perform($entities)
    {
        if (!$this->cannedMessage) {
            throw new \RuntimeException('No canned message has been specified');
        }

        $original = $this->cannedMessage->getMessage();

        $footprint = clone $original;
        $type = $footprint->getFootprintType();

        if (!$type) {
            throw new \RuntimeException('No type set for canned footprint');
        }
        // TODO: This should specify which entity to perform the footprint on
        $type->setForeignEntity(reset($entities));
//        $type->setFootprint($footprint);
//        $footprint->setFootprintType($type);


        $this->messageService->send($footprint);
    }

    public function requires()
    {
        return 'Vivait\Common\Model\ContactEmailInterface';
    }

    /**
     * Gets messageService
     * @return MessageService
     */
    public function getMessageService()
    {
        return $this->messageService;
    }

    /**
     * Sets messageService
     * @param MessageService $messageService
     * @return $this
     */
    public function setMessageService($messageService)
    {
        $this->messageService = $messageService;

        return $this;
    }

    /**
     * Gets cannedMessage
     * @return CannedMessage
     */
    public function getCannedMessage()
    {
        return $this->cannedMessage;
    }

    /**
     * Sets cannedMessage
     * @param CannedMessage $cannedMessage
     * @return $this
     */
    public function setCannedMessage($cannedMessage)
    {
        $this->cannedMessage = $cannedMessage;

        return $this;
    }
}

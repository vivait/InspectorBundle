<?php

namespace Vivait\InspectorBundle\Entity\Action;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Vivait\InspectorBundle\Entity\Action;
use Vivait\InspectorBundle\Form\ActionType;
use Vivait\Voter\Model\ActionInterface;

/**
 * Action
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Vivait\InspectorBundle\Entity\SendEmailActionRepository")
 */
class SendEmail extends Action
{
    /**
     * @var string
     *
     * @ORM\Column(name="recipient", type="string")
     */
    private $recipient;

    /**
     * @var string
     *
     * @ORM\Column(name="sender", type="string")
     */
    private $sender;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * Gets from
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Sets from
     * @param string $sender
     * @return $this
     */
    public function setSender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Gets message
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets message
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Gets to
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Sets to
     * @param string $recipient
     * @return $this
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function loadService(ContainerInterface $container) {
        $sendemail = $container->get('vivait_inspector.action.sendemail');
        $sendemail->setEntity($this);

        return $sendemail;
    }

    /**
     * @return ActionType
     */
    public function getFormType()
    {
        return 'vivait_inspectorbundle_action_sendemail';
    }
}

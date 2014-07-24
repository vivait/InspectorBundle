<?php

namespace Vivait\InspectorBundle\Service\Action;

use Doctrine\ORM\Mapping as ORM;
use Vivait\InspectorBundle\Entity\Action;
use Vivait\Voter\Model\ActionInterface;
use Vivait\InspectorBundle\Entity\Action\SendEmail as SendEmailEntity;

class SendEmailService implements ActionInterface
{
    /**
     * @var SendEmailEntity
     */
    protected $entity;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function perform($entities)
    {
        if (!$this->entity) {
            throw new \RuntimeException('No entity has been specified');
        }

        $message   = \Swift_Message::newInstance()
          ->setSubject('Testing')
          ->setFrom([$this->entity->getSender()])
          ->setTo($this->entity->getRecipient())
          ->setBody($this->entity->getMessage());

        return (bool)$this->mailer->send($message);
    }

    public function requires()
    {
        return 'Vivait\Common\Model\ContactEmailInterface';
    }

    /**
     * Gets entity
     * @return SendEmailEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Sets entity
     * @param SendEmailEntity $entity
     * @return $this
     */
    public function setEntity(SendEmailEntity $entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Gets mailer
     * @return \Swift_Mailer
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * Sets mailer
     * @param \Swift_Mailer $mailer
     * @return $this
     */
    public function setMailer(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;

        return $this;
    }
}

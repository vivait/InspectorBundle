<?php

namespace Vivait\InspectorBundle\Features\Context;

use Swift_Events_SendEvent;
use Swift_Events_SendListener;

class SwiftMailCollector implements Swift_Events_SendListener
{

    private $messages = [];

    public function beforeSendPerformed(Swift_Events_SendEvent $evt)
    {
    }

    public function sendPerformed(Swift_Events_SendEvent $evt)
    {
        $this->messages[] = $evt->getMessage();
    }

    /**
     * Gets messages
     * @return \Swift_Message[]
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
<?php

namespace Vivait\InspectorBundle\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Vivait\Voter\Dispatcher\ActionDispatcher;
use Vivait\Voter\Model\EntityEvent;
use Vivait\InspectorBundle\Entity\Inspection;
use Vivait\Voter\Model\VoterInterface;

class RegisterService
{
    private $voters;

    function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function registerVoter($alias, VoterInterface $voter){
        if (isset($this->voters[$alias])) {
            throw new \InvalidArgumentException(sprintf('Voter with alias %s already exists', $alias));
        }

        $this->voters = $voter;
    }

    /**
     * @return array
     * @todo This needs to be dynamic
     */
    public function getVotersList() {
        return [
            'And',
            'Or'
        ];
    }
} 
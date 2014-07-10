<?php

namespace Vivait\InspectorBundle\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Vivait\Voter\Model\VoterInterface;

class VoterRegistryService
{
    private $voters;

    /**
     * @param array $voters
     */
    function __construct(array $voters = array())
    {
        $this->voters = $voters;
    }

    /**
     * @param $label
     * @param VoterInterface $voter
     */
    public function addVoter($label, VoterInterface $voter){
        if (isset($this->voters[$label])) {
            throw new \InvalidArgumentException(sprintf('Voter with label %s already exists', $label));
        }

        $this->voters[$label] = $voter;
    }

    /**
     * @return array
     */
    public function getList() {
        return array_keys($this->voters);
    }
} 
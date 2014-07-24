<?php

namespace Vivait\InspectorBundle\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Vivait\Voter\Model\VoterInterface;

class VoterRegistryService
{
    private $voters = [];
    private $voter_labels = [];

    /**
     * @param array $voters
     * @param array $voter_labels
     */
    function __construct(array $voters = [], array $voter_labels = [])
    {
        $this->voters = $voters;
        $this->voter_labels = $voter_labels;
    }

    /**
     * @param                $label
     * @param VoterInterface $voter
     */
    public function addVoter($alias, $label, VoterInterface $voter)
    {
        if (isset($this->voters[$alias])) {
            throw new \InvalidArgumentException(sprintf('Voter with alias %s already exists', $alias));
        }

        $this->voters[$alias] = $voter;
        $this->voter_labels[$alias] = $label;
    }

    /**
     * @param $alias
     * @return Voter
     */
    public function getVoter($alias)
    {
        if (!isset($this->voters[$alias])) {
            throw new \OutOfBoundsException(sprintf('Tried to load non-existent voter "%s"', $alias));
        }

        return $this->voters[$alias];
    }

    /**
     * Gets voters
     * @return array
     */
    public function getVoters()
    {
        return $this->voters;
    }


    /**
     * @return array
     */
    public function getList()
    {
        return $this->voter_labels;
    }
} 
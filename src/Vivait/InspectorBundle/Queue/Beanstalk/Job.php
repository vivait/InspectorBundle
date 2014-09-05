<?php

namespace Vivait\InspectorBundle\Queue\Beanstalk;

class Job extends \Pheanstalk_Job
{
    protected $inspection;

    public function __construct($id, $data, $inspection)
    {
        parent::__construct($id, $data);
        $this->inspection = $inspection;
    }

    public function getInspection()
    {
        return $this->inspection;
    }
}
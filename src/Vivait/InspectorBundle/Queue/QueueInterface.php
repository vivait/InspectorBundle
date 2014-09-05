<?php
/*
 * Copyright CloseToMe 2011/2012
 * Released under the The MIT License
 */

namespace Vivait\InspectorBundle\Queue;


use Vivait\InspectorBundle\Queue\Beanstalk;
use Vivait\InspectorBundle\Queue\Beanstalk\Job;

interface QueueInterface {

    public function put($inspection, $data, $delay = 0);

    public function get();

    public function delete($job);
}
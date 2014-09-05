<?php

namespace Vivait\InspectorBundle\Queue;

use Vivait\InspectorBundle\Queue\Beanstalk\Job;

class Beanstalk implements QueueInterface
{
    const PRIORITY = 20;
    const TTL = 3600;

    protected $beanstalk;
    protected $tube;

    /**
     * @param \Pheanstalk $beanstalk
     * @param string $tube
     */
    public function __construct($beanstalk, $tube)
    {
        $this->beanstalk = $beanstalk;
        $this->tube = $tube;
    }

    public function put($inspection, $data, $delay = 0)
    {
        $job = json_encode([$inspection, $data]);

        $this->beanstalk->useTube($this->tube);
        $this->beanstalk->put($job, self::PRIORITY, $delay, self::TTL);
    }

    public function get()
    {
        $this->beanstalk->watch($this->tube);
        $job = $this->beanstalk->reserve();

        list($inspection, $data) = @json_decode($job->getData(), true);

        return new Job($job->getId(), $data, $inspection);
    }

    public function delete($job)
    {
        $this->beanstalk->delete($job);
    }
}
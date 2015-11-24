<?php

namespace Laraveles\Events;

use Laraveles\Job;

class JobWasApproved
{
    /**
     * @var Job
     */
    private $job;

    /**
     * JobWasApproved constructor.
     *
     * @param Job $job
     */
    public function __construct(Job $job)
    {
        $this->job = $job;
    }
}
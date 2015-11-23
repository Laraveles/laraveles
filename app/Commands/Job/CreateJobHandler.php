<?php

namespace Laraveles\Commands\Job;

use Laraveles\Job;
use Laraveles\Events\JobWasCreated;
use Laraveles\Commands\CommandHandler;

class CreateJobHandler extends CommandHandler
{
    public function handle(CreateJob $command)
    {
        $job = new Job();

        $this->fire(new JobWasCreated($job));
    }
}

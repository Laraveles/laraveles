<?php

namespace Laraveles\Commands\Job;

use Laraveles\Commands\CommandHandler;
use Laraveles\Events\JobWasCreated;

class CreateJobHandler extends CommandHandler
{
    public function handle(CreateJob $command)
    {
        $job =

        $this->fire(new JobWasCreated($job));
    }
}

<?php

namespace Laraveles\Listeners;

use Laraveles\Events\UserWasCreated;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Laraveles\Commands\Auth\SendConfirmation;

class EmailUserActivation
{
    use DispatchesJobs;

    /**
     * @param UserWasCreated $event
     */
    public function handle(UserWasCreated $event)
    {
        $this->dispatch(
            new SendConfirmation($event->user->token)
        );
    }
}
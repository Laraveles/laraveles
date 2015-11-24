<?php

namespace Laraveles\Listeners;

use Laraveles\Events\UserWasCreated;
use Laraveles\Commands\Auth\SendConfirmation;

class EmailUserActivation
{
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
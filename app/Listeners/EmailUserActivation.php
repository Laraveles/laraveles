<?php

namespace Laraveles\Listeners;

use Laraveles\Events\AbstractEvent;
use Laraveles\Events\UserWasCreated;
use Laraveles\Commands\Auth\SendConfirmation;

class EmailUserActivation extends AbstractEvent
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
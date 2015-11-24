<?php

namespace Laraveles\Listeners;

use Laraveles\Events\JobWasCreated;
use Laraveles\Mailer\JobMailer;

class NotifyJobCreation
{
    /**
     * @var JobMailer
     */
    protected $mailer;

    /**
     * SendConfirmationHandler constructor.
     *
     * @param JobMailer $mailer
     */
    public function __construct(JobMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param JobWasCreated $event
     */
    public function handle(JobWasCreated $event)
    {
        $job = $event->job;

        $this->mailer->administrator($job);
    }
}
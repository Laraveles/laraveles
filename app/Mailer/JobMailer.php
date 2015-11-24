<?php

namespace Laraveles\Mailer;

class JobMailer extends Mailer
{
    /**
     * Send an email to the administrator.
     *
     * @param $job
     */
    public function administrator($job)
    {
        $this->send(
            'job.email.administrator',
            compact('job'),
            'admin@laraveles.com',
            'Nuevo empleo pendiente de moderación'
        );
    }
}
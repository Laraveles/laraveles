<?php

namespace Laraveles\Mailer;

use Illuminate\Contracts\Mail\Mailer as Mail;
use Laraveles\User;

class Mailer
{
    /**
     * @var Mail
     */
    protected $mailer;
    
    /**
     * Mailer constructor.
     *
     * @param Mail $mailer
     */
    public function __construct(Mail $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Email delivery.
     *
     * @param $view
     * @param $data
     * @param $to
     * @param $subject
     */
    protected function send($view, $data, $to, $subject)
    {
        if ($to instanceof User) {
            $to = $to->email;
        }

        $this->mailer->send($view, $data, function ($m) use ($to, $subject) {
            $m->to($to)
              ->subject($subject);
        });
    }
}

<?php

namespace Laraveles\Mailer;

class AuthMailer extends Mailer
{
    /**
     * Sends the mail confirmation email.
     *
     * @param $email
     * @param $token
     */
    public function confirmation($email, $token)
    {
        $this->send('auth.email.confirmation', compact('token'), $email, 'Confirmación de email');
    }
}
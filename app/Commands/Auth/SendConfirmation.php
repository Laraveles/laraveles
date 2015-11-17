<?php

namespace Laraveles\Commands\Auth;

class SendConfirmation
{
    /**
     * The token to activate by.
     *
     * @var string
     */
    public $token;

    /**
     * SendConfirmationCommand constructor.
     *
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }
}
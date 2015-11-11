<?php

namespace Laraveles\Commands\Auth;

class SocialAuthenticateUser
{
    /**
     * The provider name.
     *
     * @var string
     */
    public $driver;

    /**
     * SocialAuthenticateUser constructor.
     *
     * @param      $driver
     */
    public function __construct($driver)
    {
        $this->driver = $driver;
    }
}
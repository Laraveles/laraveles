<?php

namespace Laraveles\Commands\Auth;

class AuthenticateUser
{
    /**
     * Username or email.
     *
     * @var
     */
    public $username;

    /**
     * Password.
     *
     * @var
     */
    public $password;

    /**
     * Session should be remembered.
     *
     * @var
     */
    public $remember;

    /**
     * AuthenticateUser constructor.
     *
     * @param      $username
     * @param      $password
     * @param bool $remember
     */
    public function __construct($username, $password, $remember = false)
    {
        $this->username = $username;
        $this->password = $password;
        $this->remember = $remember;
    }
}
<?php

namespace Laraveles\Exceptions\Auth;

class InactiveUserException extends \Exception
{
    /**
     * @var User
     */
    protected $user;

    /**
     * InactiveUserException constructor.
     *
     * @param string $user
     */
    public function __construct($user)
    {
        $this->user = $user;

        parent::__construct();
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
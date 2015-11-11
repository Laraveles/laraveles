<?php

namespace Laraveles\Exceptions\Auth;

class SocialUserNotFoundException extends \Exception
{
    /**
     * @var User
     */
    protected $user;

    /**
     * SocialUserNotFoundException constructor.
     *
     * @param $user
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
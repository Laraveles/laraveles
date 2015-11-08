<?php

namespace Laraveles\Repositories;

use Laraveles\User;

class UserRepository
{
    /**
     * @param $provider
     * @param $user
     * @return mixed
     */
    public function findByProvider($provider, $user)
    {
        return User::findByProvider($provider, $user);
    }
}
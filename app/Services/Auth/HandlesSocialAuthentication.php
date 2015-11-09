<?php

namespace Laraveles\Services\Auth;

use Laraveles\User;

interface HandlesSocialAuthentication
{
    /**
     * Handling any error found during the authentication process.
     *
     * @return mixed
     */
    public function errorFound();

    /**
     * Handling user was found in database.
     *
     * @param User $user
     * @return mixed
     */
    public function userExists(User $user);

    /**
     * Not able to found a user in database handler.
     *
     * @param $socialUser
     * @return mixed
     */
    public function userDoesNotExist($socialUser);
}
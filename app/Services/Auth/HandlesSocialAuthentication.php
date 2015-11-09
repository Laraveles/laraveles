<?php

namespace Laraveles\Services\Auth;

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
     * @param $user
     * @return mixed
     */
    public function userExists($user);

    /**
     * Not able to found a user in database handler.
     *
     * @return mixed
     */
    public function userDoesNotExist();
}
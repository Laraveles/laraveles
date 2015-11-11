<?php

namespace Laraveles\Commands\Auth;

use Laraveles\Commands\Command;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Events\Dispatcher;
use Laraveles\Exceptions\Auth\InactiveUserException;

class AuthenticateUserHandler extends Command
{
    /**
     * @var Guard
     */
    protected $auth;

    /**
     * AuthenticateUserHandler constructor.
     *
     * @param Guard      $auth
     * @param Dispatcher $dispatcher
     */
    public function __construct(Guard $auth, Dispatcher $dispatcher)
    {
        $this->auth = $auth;

        parent::__construct($dispatcher);
    }
    
    /**
     * Handles the command operation.
     *
     * @param AuthenticateUser $user
     * @return mixed
     * @throws InactiveUserException
     */
    public function handle(AuthenticateUser $user)
    {
        $credentials = $this->getCredentials($user);

        // After guessing what field we should use for logging in, we'll just
        // attempt to login with those credentials. If authentication pass
        // we have first to check wether user account has been activated.
        if (! $this->auth->attempt($credentials, $user->remember)) {
            return false;
        }

        $user = $this->auth->user();

        if (! $user->isActive()) {
            $this->auth->logout();

            throw new InactiveUserException($user);
        }

        return $user;
    }

    /**
     * Will check if the user is active.
     *
     * @param $user
     * @return void
     * @throws InactiveUserException
     */
    protected function userIsActive($user)
    {
        if (! $user->isActive()) {
            throw new InactiveUserException($user);
        }
    }

    /**
     * Getting the credentials from the command. Will also swap the credential
     * username based on the input (username / email).
     *
     * @param $user
     * @return mixed
     */
    protected function getCredentials($user)
    {
        $credentials = array_only((array) $user, ['username', 'password']);

        if (filter_var($user->username, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $credentials['username'];
            unset($credentials['username']);
        }

        return $credentials;
    }
}

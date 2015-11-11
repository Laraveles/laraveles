<?php

namespace Laraveles\Commands\Auth;

use Laraveles\Commands\Command;
use Illuminate\Contracts\Auth\Guard;
use Laraveles\Repositories\UserRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Laraveles\Exceptions\Auth\SocialUserNotFoundException;

class SocialAuthenticateUserHandler extends Command
{
    /**
     * @var Guard
     */
    protected $auth;

    /**
     * @var Socialite
     */
    protected $socialite;

    /**
     * @var UserRepository
     */
    protected $user;

    /**
     * SocialAuthenticateUserHandler constructor.
     *
     * @param Socialite      $socialite
     * @param Guard          $auth
     * @param UserRepository $user
     * @param Dispatcher     $dispatcher
     */
    public function __construct(
        Socialite $socialite,
        Guard $auth,
        UserRepository $user,
        Dispatcher $dispatcher
    ) {
        $this->auth = $auth;
        $this->socialite = $socialite;
        $this->user = $user;

        parent::__construct($dispatcher);
    }

    /**
     * @param SocialAuthenticateUser $provider
     * @return mixed
     * @throws SocialUserNotFoundException
     */
    public function handle(SocialAuthenticateUser $provider)
    {
        $driver = $provider->driver;

        $user = $this->socialite->driver($driver)->user();

//        $this->formatObject($driver, $user);

        // After retrieving the user details from the OAuth provider, we'll add
        // some standard formatting. If the user is found in our records, we
        // will just autentify it. Otherwise, exceptions will be thrown.
        $localUser = $this->findUser(
            $driver, $user->getId(), $user->getEmail()
        );
        if ($localUser) {
            $this->auth->login($localUser);
            return $localUser;
        }

        throw new SocialUserNotFoundException($user);
    }

    /**
     * Formatting the provider user with custom attributes.
     *
     * @param $driver
     * @param $user
     */
    protected function formatObject($driver, $user)
    {
        // Setting the provider name + _id field will match the convention used
        // for storing the unique provider user identification number in the
        // users table. As an example: github_id, google_id, facebook_id.
        $field = $driver . '_id';

        $user->username = $user->getNickname();
        $user->{$field} = $user->getId();
    }

    /**
     * Retrieve the OAuth user from database if exists.
     *
     * @param $driver
     * @param $identifier
     * @param $email
     * @return mixed
     * @throws \ModelNotFoundException
     */
    protected function findUser($driver, $identifier, $email)
    {
        try {
            return $this->user->findByProviderOrEmail($driver, $identifier, $email);
        } catch (\ModelNotFoundException $e) {
            throw $e;
        }
    }
}

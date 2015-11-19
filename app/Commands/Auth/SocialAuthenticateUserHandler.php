<?php

namespace Laraveles\Commands\Auth;

use Illuminate\Contracts\Auth\Guard;
use Laraveles\Commands\CommandHandler;
use Laraveles\Repositories\UserRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Laraveles\Exceptions\Auth\SocialUserNotFoundException;

class SocialAuthenticateUserHandler extends CommandHandler
{
    /**
     * The Guard instance.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * The Socialite instance.
     *
     * @var Socialite
     */
    protected $socialite;

    /**
     * The user repository instance.
     *
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
     * Retrieve the OAuth user from database if exists.
     *
     * @param $driver
     * @param $identifier
     * @param $email
     * @return mixed
     * @throws \ModelNotFoundException
     */
    protected function findUser($driver, $identifier, $email = null)
    {
        if ($user = $this->user->findByProviderOrEmail($driver, $identifier, $email)) {
            $this->sync($user, $driver, $identifier, $email);
        }

        return $user;
    }

    /**
     * In case the user was already existing into the database, we'll sync
     * the provider id and the email with the data provided by the OAuth.
     *
     * @param $user
     * @param $driver
     * @param $identifier
     * @param $email
     */
    protected function sync($user, $driver, $identifier, $email = null)
    {
        $field = $driver . '_id';

        if (! is_null($email) && empty($user->getAttribute('email'))) {
            $user->setAttribute('email', $email);
        }
        // We'll set the users email to the email provided if empty and any is
        // provided. Also the OAuth driver id will be established in case it
        // was not found. This will would link the provider to the user.
        if (empty($user->getAttribute($field))) {
            $user->setAttribute($field, $identifier);
        }

        $user->save();
    }
}

<?php namespace Laraveles\Services\Auth;

use Laraveles\Repositories\UserRepository;
use Laravel\Socialite\Contracts\Factory as Socialite;

class SocialAuthenticator
{
    /**
     * Socialite instance.
     *
     * @var Socialite
     */
    protected $socialite;

    /**
     * User's repository instance.
     *
     * @var UserRepository
     */
    protected $user;

    /**
     * Authentication handler instance.
     *
     * @var HandlesSocialAuthentication
     */
    protected $handler;

    /**
     * SocialAuthenticator constructor.
     *
     * @param Socialite                   $socialite
     * @param UserRepository              $user
     * @param HandlesSocialAuthentication $handler
     */
    public function __construct(
        Socialite $socialite,
        UserRepository $user,
        HandlesSocialAuthentication $handler
    ) {
        $this->socialite = $socialite;
        $this->user = $user;
        $this->handler = $handler;
    }

    /**
     * Authentication request to the provider.
     *
     * @param $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        return $this->socialite->driver($provider)->redirect();
    }

    /**
     * Check if user exists in the system.
     *
     * @param $provider
     * @return bool
     */
    public function authenticate($provider)
    {
        try {
            $socialUser = $this->socialite->driver($provider)->user();
        } catch (\Exception $e) {
            return $this->handler->errorFound();
        }

        $this->formatUser($socialUser);

        return $this->findUser($provider, $socialUser);
    }

    /**
     * Gets the user from database if exists.
     *
     * @param $provider
     * @param $socialUser
     * @return mixed
     */
    protected function findUser($provider, $socialUser)
    {
        if ($user = $this->getUser($provider, $socialUser)) {
            return $this->handler->userExists($user);
        }

        return $this->handler->userDoesNotExist();
    }

    /**
     * Formatting the provider user with custom attributes.
     *
     * @param $user
     */
    protected function formatUser($user)
    {
        $user->username = $user->getNickname();
    }

    /**
     * Retrieve the user from persistance.
     *
     * @param $provider
     * @param $user
     * @return mixed
     */
    protected function getUser($provider, $user)
    {
        return $this->user->findByProvider($provider, $user);
    }

    /**
     * Set the authenticaton handler.
     *
     * @param HandlesSocialAuthentication $handler
     */
    public function setHandler(HandlesSocialAuthentication $handler)
    {
        $this->handler = $handler;
    }
}

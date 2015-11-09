<?php namespace Laraveles\Services\Auth;

use Laraveles\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Guard as Auth;
use Laravel\Socialite\Contracts\Factory as Socialite;

class SocialAuthenticator
{
    /**
     * Guard instance.
     *
     * @var Auth
     */
    protected $auth;

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
     * @param Auth                        $auth
     * @param UserRepository              $user
     * @param HandlesSocialAuthentication $handler
     */
    public function __construct(
        Socialite $socialite,
        Auth $auth,
        UserRepository $user,
        HandlesSocialAuthentication $handler
    ) {
        $this->socialite = $socialite;
        $this->auth = $auth;
        $this->user = $user;
        $this->handler = $handler;
    }

    /**
     * @param $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        return $this->socialite->driver($provider)->redirect();
    }

    /**
     * @param $provider
     * @return bool
     */
    public function authenticate($provider)
    {
        try {
            $socialUser = $this->socialite->driver($provider)->user();
        } catch (\Exception $e) {
            $this->handler->errorFound();
        }

        $this->formatUser($socialUser);

        return $this->findUser($provider, $socialUser);
    }

    /**
     * Gets the user from database if exists.
     *
     * @param $socialUser
     * @return mixed
     */
    protected function findUser($provider, $socialUser)
    {
        if ($user = $this->getUser($provider, $socialUser)) {
            $this->auth->login($user);
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
}

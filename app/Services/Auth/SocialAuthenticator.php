<?php namespace Laraveles\Services\Auth;

use Laraveles\User;
use Illuminate\Contracts\Auth\Guard as Auth;
use Laravel\Socialite\Contracts\Factory as Socialite;

class SocialAuthenticator
{
    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var Socialite
     */
    protected $socialite;

    /**
     * SocialAuthenticator constructor.
     *
     * @param Socialite $socialite
     * @param Auth      $auth
     */
    public function __construct(Socialite $socialite, Auth $auth)
    {
        $this->socialite = $socialite;
        $this->auth = $auth;
    }

    /**
     * @param $provider
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        return $this->socialite->driver($provider)->redirect();
    }

    /**
     * @param $provider
     *
     * @return bool
     */
    public function authenticate($provider)
    {
        $socialUser = $this->socialite->driver($provider)->user();

        $this->formatUser($socialUser);

        if ($user = $this->getUser($provider, $socialUser)) {
            $this->auth->login($user);
        }

        return $user;
    }

    /**
     * Formatting the provider user with custom attributes.
     *
     * @param $user
     */
    public function formatUser($user)
    {
        $user->username = $user->getNickname();
    }

    /**
     * @param $provider
     * @param $user
     *
     * @return mixed
     */
    protected function getUser($provider, $user)
    {
        return User::findByProvider($provider, $user->getId());
    }
}
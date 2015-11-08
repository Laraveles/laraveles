<?php namespace Laraveles\Services\Auth;

use Laraveles\Repositories\UserRepository;
use Laraveles\User;
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
    private $user;

    /**
     * SocialAuthenticator constructor.
     *
     * @param Socialite      $socialite
     * @param Auth           $auth
     * @param UserRepository $user
     */
    public function __construct(
        Socialite $socialite,
        Auth $auth,
        UserRepository $user
    ) {
        $this->socialite = $socialite;
        $this->auth = $auth;
        $this->user = $user;
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

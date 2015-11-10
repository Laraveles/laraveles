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
     * @param Socialite      $socialite
     * @param UserRepository $user
     */
    public function __construct(Socialite $socialite, UserRepository $user)
    {
        $this->socialite = $socialite;
        $this->user = $user;
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
        }
        catch (\Exception $e) {
            return $this->handler->errorFound();
        }

        $this->formatUser($provider, $socialUser);

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
        if ($user = $this->getUser($provider, $socialUser->getId())) {
            return $this->handler->userExists($user);
        }

        return $this->handler->userDoesNotExist($socialUser);
    }

    /**
     * Formatting the provider user with custom attributes.
     *
     * @param $user
     */
    protected function formatUser($provider, $user)
    {
        // Setting the provider name + _id field will match the convention used
        // for storing the unique provider user identification number in the
        // users table. As an example: github_id, google_id, facebook_id.
        $field = $provider . '_id';

        $user->username = $user->getNickname();
        $user->{$field} = $user->getId();
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

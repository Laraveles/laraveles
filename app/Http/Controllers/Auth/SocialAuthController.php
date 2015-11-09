<?php

namespace Laraveles\Http\Controllers\Auth;

use Laraveles\User;
use Laraveles\Commands\User\CreateUser;
use Laraveles\Services\Auth\SocialAuthenticator;
use Laraveles\Services\Auth\HandlesSocialAuthentication;

class SocialAuthController extends AbstractAuthController implements HandlesSocialAuthentication
{
    /**
     * The social authenticator instance.
     *
     * @var SocialAuthenticator
     */
    protected $authenticator;

    /**
     * SocialAuthController constructor.
     *
     * @param SocialAuthenticator $authenticator
     */
    public function __construct(SocialAuthenticator $authenticator)
    {
        $authenticator->setHandler($this);
        $this->authenticator = $authenticator;
    }

    /**
     * Social login redirector.
     *
     * @param $provider
     *
     * @return mixed
     */
    public function redirect($provider)
    {
        return $this->authenticator->redirectToProvider($provider);
    }

    /**
     * Social provider response.
     *
     * @param $provider
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback($provider)
    {
        $this->authenticator->authenticate($provider);
    }

    /**
     * Handling any error found during the authentication process.
     *
     * @return mixed
     */
    public function errorFound()
    {
        return view('auth.login')->withErrors([
            'error' => 'Ocurrió un error con el proveedor social.'
        ]);
    }

    /**
     * Handling user was found in database.
     *
     * @param $user
     * @return mixed
     */
    public function userExists(User $user)
    {
        $this->loginAndRedirect($user);
    }

    /**
     * Not able to found a user in database handler.
     *
     * @param $socialUser
     * @return mixed
     */
    public function userDoesNotExist($socialUser)
    {
        $createUser = new CreateUser(['provider' => $socialUser]);

        $user = $this->dispatch($createUser);

        return $this->loginAndRedirect($user);
    }
}

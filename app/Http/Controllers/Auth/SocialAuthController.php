<?php

namespace Laraveles\Http\Controllers\Auth;

use Laraveles\Commands\User\CreateUser;
use Illuminate\Contracts\Auth\Guard as Auth;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SocialAuthController extends AbstractAuthController
{
    /**
     * @var Socialite
     */
    private $socialite;

    /**
     * SocialAuthController constructor.
     *
     * @param Auth      $auth
     * @param Socialite $socialite
     * @internal param Factory $authenticator
     */
    public function __construct(Auth $auth, Socialite $socialite)
    {
        $this->socialite = $socialite;
        parent::__construct($auth);
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
        return $this->socialite->driver($provider)->redirect();
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
        try {
            $this->dispatch(new SocialAuthenticateUser($provider));
            $this->afterLoginRedirect();
        } catch (ModelNotFoundException $e) {
            $user = $this->socialite->driver($provider)->user();

            return $this->createAndLogin($user);
        } catch (\Exception $e) {
            return $this->errorFound();
        }
    }

    /**
     * Handling any error found during the authentication process.
     *
     * @return mixed
     */
    public function errorFound()
    {
        return $this->loginRedirect()->withErrors([
            'error' => Lang::get('auth.oauth-error')
        ]);
    }

    /**
     * Creates the user and logges it in.
     *
     * @param $provider
     * @return mixed
     */
    public function createAndLogin($provider)
    {
        $user = $this->dispatch(
            new CreateUser(compact('provider'))
        );

        // If the user was not found in our records, we'll just create a new one
        // based on the information given by the OAuth provider. Once created
        // we will log it in as we assume it is a valid and activated user.
        $this->auth->login($user);

        return $this->afterLoginRedirect();
    }
}

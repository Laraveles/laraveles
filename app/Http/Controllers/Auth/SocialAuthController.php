<?php

namespace Laraveles\Http\Controllers\Auth;

use Laraveles\Commands\User\CreateUser;
use Illuminate\Contracts\Auth\Guard as Auth;
use Laraveles\Commands\Auth\SocialAuthenticateUser;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Laraveles\Exceptions\Auth\SocialUserNotFoundException;

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
     * @param $driver
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback($driver)
    {
        try {
            $this->dispatch(new SocialAuthenticateUser($driver));
            return $this->afterLoginRedirect();
        } catch (SocialUserNotFoundException $e) {
            return $this->createAndLogin($driver, $e->getUser());
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
        flash()->error(\Lang::get('auth.oauth-error'));

        return $this->loginRedirect();
    }

    /**
     * Creates the user and logges it in.
     *
     * @param $driver
     * @param $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAndLogin($driver, $provider)
    {
        $provider->driver = $driver;

        $user = $this->dispatch(
            new CreateUser(compact('provider'))
        );

        // If the user was not found in our records, we'll just create a new one
        // based on the information given by the OAuth provider. Once created
        // we will log it in as we assume it is a valid and activated user.
        $this->auth->login($user);

        flash()->info(
            "¡Bienvenido a Laraveles! Tu usuario se ha creado con la información proporcionada por {$driver}. Ya estás listo para participar."
        );

        return $this->afterLoginRedirect();
    }
}

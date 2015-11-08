<?php

namespace Laraveles\Http\Controllers\Auth;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laraveles\Services\Auth\SocialAuthenticator;

class SocialAuthController extends AbstractAuthController
{
    /**
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
        try {
            $this->authenticator->authenticate($provider);
        } catch (ModelNotFoundException $e) {
            // Redirect to the user register page
        }

        return $this->afterLoginRedirect();
    }
}

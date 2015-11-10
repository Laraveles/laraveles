<?php

namespace Laraveles\Http\Controllers\Auth;

use Illuminate\Support\Facades\Lang;
use Laraveles\Http\Requests\AuthRequest;
use Laraveles\Http\Requests\UserRequest;
use Illuminate\Contracts\Auth\Guard as Auth;

class AuthController extends AbstractAuthController
{
    /**
     * User username to login by.
     *
     * @var string
     */
    protected $username = 'username';

    /**
     * Attempting login.
     *
     * @param AuthRequest $request
     * @param Auth        $authenticator
     * @return string
     */
    public function authenticate(AuthRequest $request, Auth $authenticator)
    {
        $credentials = $this->getCredentials($request);

        // We'll try to authenticate the user. If OK, the authenticated user
        // will be redirected to the URL it was trying to get or just the
        // default route. If any error the user goes back to the login.
        if ($authenticator->attempt($credentials, $request->has('remember'))) {
            return $this->afterLoginRedirect();
        }

        return $this->loginRedirect()
                    ->withInput($request->only($this->username, 'remember'))
                    ->withErrors([
                        $this->username => Lang::get('auth.failed')
                    ]);
    }

    /**
     * Getting the credentials from the request. Will also swap the credential
     * username based on the input (username / email).
     *
     * @param $request
     * @return mixed
     */
    protected function getCredentials($request)
    {
        $credentials = $request->only($this->username, 'password');

        if (filter_var($credentials[$this->username], FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $credentials['username'];
            unset($credentials['username']);
        }

        return $credentials;
    }
}

<?php

namespace Laraveles\Http\Controllers\Auth;

use Laraveles\Http\Requests\UserRequest;

class AuthController extends AbstractAuthController
{
    /**
     * User username to login by.
     *
     * @var string
     */
    protected $username = 'username';

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Attempting login.
     *
     * @param LoginRequest $request
     *
     * @param Auth         $authenticator
     *
     * @return string
     */
    public function authenticate(AuthRequest $request, Auth $authenticator)
    {
        $credentials = $request->only($this->username, 'password');

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
}
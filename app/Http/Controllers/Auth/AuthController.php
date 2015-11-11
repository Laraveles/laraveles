<?php

namespace Laraveles\Http\Controllers\Auth;

use Illuminate\Support\Facades\Lang;
use Laraveles\Http\Requests\AuthRequest;
use Laraveles\Commands\Auth\AuthenticateUser;
use Laraveles\Exceptions\Auth\InactiveUserException;

class AuthController extends AbstractAuthController
{
    /**
     * Attempting login.
     *
     * @param AuthRequest $request
     * @return string
     */
    public function authenticate(AuthRequest $request)
    {

        try {
            $access = $this->dispatchFromArray(
                AuthenticateUser::class, $request->only('username', 'password', 'remember')
            );

            // We'll try to authenticate the user. If OK, the authenticated user
            // will be redirected to the URL it was trying to get or just the
            // default route. If any error the user goes back to the login.
            if (! $access) {
                return $this->loginRedirect()
                            ->withInput($request->only('username', 'remember'))
                            ->withErrors([
                                'error' => Lang::get('auth.failed')
                            ]);
            }
        }
        catch (InactiveUserException $e) {
            return $this->loginRedirect()
                        ->withErrors([
                            'error' => Lang::get('auth.inactive')
                        ]);
        }

        return $this->afterLoginRedirect();
    }
}

<?php

namespace Laraveles\Http\Controllers\Auth;

use Laraveles\User;
use Laraveles\Repositories\UserRepository;
use Laraveles\Http\Controllers\Controller;
use Laraveles\Commands\Auth\SendConfirmation;
use Laraveles\Http\Requests\ConfirmationRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ActivateController extends Controller
{
    /**
     * Confirmates token and activates user.
     *
     * @param UserRepository $repository
     * @param                $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate(UserRepository $repository, $token)
    {
        try {
            $user = $repository->ofToken($token);

            // Once we retrieve the user that has that token, will proceed to
            // activate. If the user is not found, we will notify the user
            // and redirect to the confirmation email request form page.
            $user->activate()
                 ->save();

            flash()->info('¡Enhorabuena! Hemos confirmado tu e-mail y activado tu cuenta. Bienvenido a Laraveles.');

            return redirect()->home();
        }
        catch (ModelNotFoundException $e) {
            flash()->error(
                "Token de validación incorrecto. Verifica que has introducido el token correctamente o solicita uno nuevo."
            );

            return redirect()->route('auth.activate.request');
        }
    }

    /**
     * Request for resending confirmation email.
     *
     * @return View
     */
    public function requestConfirmation()
    {
        return view('auth.request-confirmation');
    }

    /**
     * Sends the confirmation email.
     *
     * @param ConfirmationRequest $request
     * @return route
     */
    public function resendConfirmation(ConfirmationRequest $request)
    {
        $email = $request->only('email');
        $user = User::where('email', $email)->first();

        $this->dispatch(
            new SendConfirmation($user->getActivationToken())
        );

        flash()->info('Mensaje de confirmación reenviado. Comprueba tu bandeja de entrada y haz click en el enlace para confirmar tu correo.');

        return redirect()->home();
    }
}
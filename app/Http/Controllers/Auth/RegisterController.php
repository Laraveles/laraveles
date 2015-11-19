<?php

namespace Laraveles\Http\Controllers\Auth;

use Laraveles\Commands\User\CreateUser;
use Laraveles\Http\Controllers\Controller;
use Laraveles\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    /**
     * RegisterController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return Response
     */
    public function store(RegisterRequest $request)
    {
        try {
            $this->dispatch(
                new CreateUser($request->only('username', 'email', 'password'))
            );

            flash()->info('Bienvenido a Laraveles. Por favor, comprueba tu bandeja de entrada y confirma tu dirección de correo electrónico.');

            return redirect()->home();
        } catch (\Exception $e) {
        }
    }
}

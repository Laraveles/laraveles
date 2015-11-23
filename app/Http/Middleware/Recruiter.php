<?php

namespace Laraveles\Http\Middleware;

use Closure;

class Recruiter
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Recruiter constructor.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( ! $request->user()->recruiter) {
            flash()->info('Para interactuar con la sección de empleo, antes debes registrar tu perfil de empresa.');

            return redirect()->route('recruiter.create');
        }

        return $next($request);
    }
}

<?php

namespace Aimme\Ldap\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Aimme\Ldap\Contracts\Factory as LdapAuth;

class Authenticate
{
    /**
     * The authentication factory instance.
     *
     * @var \Aimme\Ldap\Contracts\Factory
     */
    protected $ldap;

    /**
     * Create a new middleware instance.
     *
     * @param  \Aimme\Ldap\Contracts\Factory  $ldap
     * @return void
     */
    public function __construct(LdapAuth $ldap)
    {
        $this->ldap = $ldap;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($guards);

        return $next($request);
    }

    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function authenticate(array $guards)
    {
        if (empty($guards)) {
            return $this->ldap->authenticate();
        }

        foreach ($guards as $guard) {
            if ($this->ldap->guard($guard)->check()) {
                return $this->ldap->shouldUse($guard);
            }
        }

        throw new AuthenticationException('Unauthenticated.', $guards);
    }
}

<?php

namespace Maslennikovyv\Guards;

use Closure;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class JwtGuard implements Guard
{
    use GuardHelpers;

    /**
     * Create a new authentication guard.
     *
     * @return void
     */
    public function __construct(
        UserProvider      $provider,
        protected Request $request,
        protected Closure $getSub,
    )
    {
        $this->provider = $provider;
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }
        if ($token = $this->request->bearerToken()) {
            $sub = ($this->getSub)($token);
            return $this->provider->retrieveById($sub);
        }
        return null;
    }

    public function validate(array $credentials = [])
    {
        return false;
    }
}

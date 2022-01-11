<?php


namespace Platforma\Guards;


use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class JwtGuard implements Guard
{
    use GuardHelpers;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var JwtDecoder
     */
    protected $decoder;

    /**
     * Create a new authentication guard.
     *
     * @param \Illuminate\Contracts\Auth\UserProvider $provider
     * @param \Illuminate\Http\Request $request
     * @param JwtDecoderInterface $decoder
     * @return void
     */
    public function __construct(
        UserProvider $provider,
        Request $request,
        JwtDecoderInterface $decoder
    )
    {
        $this->provider = $provider;
        $this->request = $request;
        $this->decoder = $decoder;
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $token = $this->request->bearerToken();
        if ($token) {
            $sub = $this->decoder->decode($token);
            return $this->provider->retrieveById($sub);
        }
        return null;
    }

    public function validate(array $credentials = [])
    {
        return false;
    }
}

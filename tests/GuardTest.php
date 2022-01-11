<?php

namespace Platforma\Guards\Test;

use Firebase\JWT\JWT;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Platforma\Guards\JwtDecoder;
use Platforma\Guards\JwtGuard;
use Mockery\MockInterface;

class GuardTest extends TestCase
{
    /**
     * Check Guard are working
     * @return void
     */
    public function testGuard()
    {
        $userId = Str::uuid()->toString();
        $payload = array(
            'sub' => $userId
        );
        $jwt = JWT::encode($payload, $this->private, 'RS256');
        $provider = $this->mock(UserProvider::class,
            function (MockInterface $mock) use ($userId) {
                $mock->shouldReceive('retrieveById')
                    ->with($userId)
                    ->andReturn(new User());
            }
        );
        $request = $this->mock(
            Request::class,
            function (MockInterface $mock) use ($jwt) {
                $mock->shouldReceive('bearerToken')
                    ->andReturn($jwt);
            }
        );
        $decoder = new JwtDecoder($this->public);
        $guard = new JwtGuard($provider, $request, $decoder);
        $this->assertInstanceOf(Authenticatable::class, $guard->user());
    }
}

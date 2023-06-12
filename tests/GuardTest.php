<?php

namespace Maslennikovyv\Guards\Test;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maslennikovyv\Guards\JwtGuard;
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
        $token = JWT::encode($payload, $this->private, 'RS256');
        $provider = $this->mock(UserProvider::class,
            function (MockInterface $mock) use ($userId) {
                $mock->shouldReceive('retrieveById')
                    ->with($userId)
                    ->andReturn(new User());
            }
        );
        $request = $this->mock(
            Request::class,
            function (MockInterface $mock) use ($token) {
                $mock->shouldReceive('bearerToken')
                    ->andReturn($token);
            }
        );

        $guard = new JwtGuard($provider, $request, function ($token) {
            try {
                $content = JWT::decode($token, new Key($this->public, 'RS256'));
                return property_exists($content, 'sub') ? $content->sub : null;
            } catch (Exception $e) {
                Log::debug($e->getMessage());
            }
            return null;
        });
        $this->assertInstanceOf(Authenticatable::class, $guard->user());
    }
}

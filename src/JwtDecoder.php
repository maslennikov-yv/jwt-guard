<?php


namespace Maslennikov\Guards;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Log;

/**
 * Class JwtDecoder possible implementation of JwtDecoderInterface
 * @package App\Guards
 */
class JwtDecoder implements JwtDecoderInterface
{
    /** @var string */
    private $public_key;

    /**
     * JwtDecoder constructor.
     * @param string $public_key
     */
    public function __construct($public_key)
    {
        $this->public_key = $public_key;
    }

    /**
     * @param string $token
     * @return mixed|null
     */
    public function decode(string $token)
    {
        if ($this->public_key) {
            try {
                $content = JWT::decode($token, $this->public_key, ['RS256']);
                if ($content && property_exists($content, 'sub')) {
                    return $content->sub;
                }
            } catch (\Exception $e) {
                Log::debug($e->getMessage());
            }
        }
        return null;
    }
}

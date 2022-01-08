<?php


namespace Maslennikov\Guards;


interface JwtDecoderInterface
{
    /**
     * Must decode, validate JWT and return user id
     * @param string $token
     * @return mixed
     */
    public function decode(string $token);
}

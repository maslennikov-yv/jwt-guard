<?php


namespace Maslennikov\Guards\Test;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /** @var */
    protected $private;

    /** @var */
    protected $public;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generateKeys();
    }

    private function generateKeys()
    {
        $options = array(
            "digest_alg" => "sha256",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
        $res = openssl_pkey_new($options);
        openssl_pkey_export($res, $private_key);
        $public_key_pem = openssl_pkey_get_details($res)['key'];
        $this->private = $private_key;
        $this->public = $public_key_pem;
    }
}
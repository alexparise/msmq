<?php

namespace Aztech\Tests\Ntlm;

use Aztech\Ntlm\NtlmHash;
use Aztech\Ntlm\ServerChallengeResponse;
use Aztech\Ntlm\ServerChallenge;

class ServerChallengeResponseTest extends \PHPUnit_Framework_TestCase
{

    private $challenge;

    private $password;

    protected function setUp()
    {
        $nonce  = hex2bin("0123456789abcdef");
        $flags  = 0x00;
        $target = "COMPUTER";

        $this->challenge = new ServerChallenge($nonce, $flags, $target);
        $this->password = "Password";
    }

    private function buildResponse()
    {
        $lmowfv1 = NtlmHash::lmowfv1($this->password);
        $ntowfv1 = NtlmHash::ntowfv1($this->password);
        $randomKey = hex2bin("55555555555555555555555555555555");

        return new ServerChallengeResponse($this->challenge, $randomKey, $lmowfv1, $ntowfv1);
    }

    public function testSessionBaseKey()
    {
        $expected = hex2bin("d87262b0cde4b1cb7499becccdf10784");

        $response = $this->buildResponse();

        $this->assertEquals($expected, $response->getSessionBaseKey());
    }

    public function testEncryptedSessionKey()
    {
        $expected = hex2bin("518822b1b3f350c8958682ecbb3e3cb7");

        $response = $this->buildResponse();

        $this->assertEquals($expected, $response->getEncryptedSessionKey());
    }

    public function testLmResponse()
    {
        $expected = hex2bin("98def7b87f88aa5dafe2df779688a172def11c7d5ccdef13");

        $response = $this->buildResponse();

        $this->assertEquals($expected, $response->getLmChallengeResponse());
    }

    public function testNtlmResponse()
    {
        $expected = hex2bin("67c43011f30298a2ad35ece64f16331c44bdbed927841f94");

        $response = $this->buildResponse();

        $this->assertEquals($expected, $response->getNtlmChallengeResponse());
    }
}

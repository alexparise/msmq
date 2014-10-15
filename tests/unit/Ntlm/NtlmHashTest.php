<?php

namespace Aztech\Tests\Ntlm;

use Aztech\Ntlm\NtlmHash;
use Aztech\Ntlm\ServerChallengeResponse;
class NtlmHashTest extends \PHPUnit_Framework_TestCase
{
    public function testLmowfv1()
    {
        $password = "Password";
        $lmowfv1  = NtlmHash::lmowfv1($password);

        $expected = hex2bin('e52cac67419a9a224a3b108f3fa6cb6d');

        $this->assertEquals($expected, $lmowfv1);
    }

    public function testNtowfv1()
    {
        $password = "Password";
        $ntowfv1  = NtlmHash::ntowfv1($password);

        $expected = hex2bin('a4f49c406510bdcab6824ee7c30fd852');

        $this->assertEquals($expected, $ntowfv1);
    }
}

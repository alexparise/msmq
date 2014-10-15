<?php

namespace Aztech\Tests\Ntlm;

use Aztech\Ntlm\Client;
use Aztech\Ntlm\Random\StaticGenerator;
class ClientTest extends \PHPUnit_Framework_TestCase
{

    private $challenge   = "4e544c4d53535000020000000c000c0038000000338202e20123456789abcdef00000000000000000000000000000000060070170000000f530065007200760065007200";

    private $authenticate = "4e544c4d5353500003000000180018006c00000018001800840000000c000c00480000000800080054000000100010005c000000100010009c000000358280e20501280a0000000f44006f006d00610069006e00550073006500720043004f004d005000550054004500520098def7b87f88aa5dafe2df779688a172def11c7d5ccdef1367c43011f30298a2ad35ece64f16331c44bdbed927841f94518822b1b3f350c8958682ecbb3e3cb7";

    public function testAuthenticateMessage()
    {
        $sessionKey = hex2bin("55555555555555555555555555555555");
        $generator = new StaticGenerator($sessionKey);

        $client = new Client('User', 'Password', 'Domain', 'Domain', 'COMPUTER');
        $client->setGenerator($generator);

        $challengeData = hex2bin($this->challenge);
        $challengeMessage = $client->parseChallenge($challengeData);
        $authenticateBytes = $client->getAuthPacket($challengeMessage);

        $this->assertEquals(hex2bin($this->authenticate), $authenticateBytes);
    }
}

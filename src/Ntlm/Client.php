<?php

namespace Aztech\Ntlm;

use Aztech\Ntlm\Message\Parser as MessageParser;
use Aztech\Ntlm\Message\ChallengeMessage;
use Aztech\Util\Hash;
use Aztech\Util\Text;
use Aztech\Net\PacketWriter;

class Client
{

    private $user;

    private $userDomain;

    private $domain;

    private $machine;

    private $lmHash;

    private $ntHash;

    private $messageFactory;

    private $messageParser;

    public function __construct($user, $password, $userDomain, $domain, $machine)
    {
        $this->user = $user;
        $this->password = $password;
        $this->userDomain = $userDomain;
        $this->domain = $domain;
        $this->machine = $machine;

        $this->lmHash = Hash::hashLm($password);
        $this->ntHash = Hash::hashNt($password);

        echo PHP_EOL . 'Hashes' . PHP_EOL;
        Text::dumpHex($this->lmHash);
        Text::dumpHex($this->ntHash);
        echo PHP_EOL;

        $this->messageFactory = new MessageFactory();
        $this->messageParser = new MessageParser();
    }

    public function getNegotiatePacket()
    {
        $header = $this->getHeader(NTLMSSP::MSG_NEGOTIATE);
        $packet = $this->messageFactory->negotiate($this->domain, $this->machine);

        $message = $header . $packet->getContent(strlen($header));

        return $message;
    }

    private function getHeader($type)
    {
        return NTLMSSP::SIGNATURE . chr(0) . pack('V', $type);
    }

    public function parseChallenge($data)
    {
        return $this->messageParser->parse($data);
    }

    public function getAuthPacket(ChallengeMessage $challenge)
    {
        $user = $this->user;
        $machine = $this->machine;
        $userDomain = $this->userDomain;
        $lmHash = $this->lmHash;
        $ntHash = $this->ntHash;

        $header = $this->getHeader(NTLMSSP::MSG_AUTH);
        $body = $this->messageFactory->authenticate($challenge, $user, $machine, $userDomain, $lmHash, $ntHash);
        $packet = $header . $body->getContent(strlen($header));

        return $packet;
    }

    public function getAuthVerifier()
    {
        $writer = new PacketWriter();

        $writer->writeUInt32(1);
        $writer->write(hex2bin('cc94206081ea6f5b00000000'));

        return $writer->getBuffer();
    }
}

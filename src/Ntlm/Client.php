<?php

namespace Aztech\Ntlm;

use Aztech\Ntlm\Message\Parser as MessageParser;
use Aztech\Ntlm\Message\ChallengeMessage;
use Aztech\Util\Hash;
use Aztech\Util\Text;
use Aztech\Net\PacketWriter;
use Aztech\Net\Buffer\BufferWriter;
use Aztech\Ntlm\Random\OpenSslGenerator;

class Client
{

    private $generator;

    private $messageFactory;

    private $messageParser;

    private $session;

    private $password;

    public function __construct($user, $password, $userDomain, $domain, $machine)
    {
        $this->generator = new OpenSslGenerator();
        $this->messageFactory = new MessageFactory();
        $this->messageParser = new MessageParser();
        $this->password = $password;
        $this->session = new Session($machine, $user, $userDomain);
    }

    public function getAuthPacket(ChallengeMessage $challenge)
    {
        $header = $this->getHeader(NTLMSSP::MSG_AUTH);
        $auth = $this->getAuthMessage($challenge);

        return $header . $auth->getContent(strlen($header));
    }

    public function getNegotiatePacket()
    {
        $header = $this->getHeader(NTLMSSP::MSG_NEGOTIATE);
        $packet = $this->messageFactory->negotiate($this->session->getUserDomain(), $this->session->getLocalMachine());

        $message = $header . $packet->getContent(strlen($header));

        return $message;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function parseChallenge($data)
    {
        return $this->messageParser->parse($data);
    }

    public function setGenerator(RandomGenerator $generator)
    {
        $this->generator = $generator;
    }

    private function getAuthMessage(ChallengeMessage $challenge)
    {
        $auth = $this->messageFactory->authenticate($this->generator, $challenge, $this->session, $this->password);

        return $auth;
    }

    private function getHeader($type)
    {
        return NTLMSSP::SIGNATURE . chr(0) . pack('V', $type);
    }
}

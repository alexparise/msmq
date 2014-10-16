<?php

namespace Aztech\Ntlm;

use Aztech\Net\PacketWriter;
use Aztech\Net\Buffer\BufferWriter;
use Aztech\Ntlm\Message\AuthMessage;
use Aztech\Ntlm\Message\ChallengeMessage;
use Aztech\Ntlm\Message\Parser as MessageParser;
use Aztech\Ntlm\Random\OpenSslGenerator;
use Aztech\Ntlm\Rpc\NtlmAuthenticationStrategy;
use Aztech\Rpc\Auth\AuthenticationStrategyProvider;
use Aztech\Util\Hash;
use Aztech\Util\Text;

class Client implements AuthenticationStrategyProvider
{

    private $authStrategy;

    private $messageFactory;

    private $messageParser;

    private $session;

    public function __construct(ClientSession $session)
    {
        $this->authStrategy = new NtlmAuthenticationStrategy($this);
        $this->messageFactory = new MessageFactory();
        $this->messageParser = new MessageParser();
        $this->session = $session;
    }

    public function getAuthPacket(ChallengeMessage $challenge)
    {
        $message = $this->getAuthMessage($challenge);
        $header = $this->getHeader($message);

        return $header . $message->getContent(strlen($header));
    }

    public function getNegotiatePacket()
    {
        $message = $this->messageFactory->negotiate($this->session);
        $header = $this->getHeader($message);

        $message = $header . $message->getContent(strlen($header));

        return $message;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function getStrategy()
    {
        return $this->authStrategy;
    }

    /**
     *
     * @param unknown $data
     * @return \Aztech\Ntlm\Message\ChallengeMessage
     */
    public function parseChallenge($data)
    {
        return $this->messageParser->parse($data);
    }

    /**
     *
     * @param ChallengeMessage $challenge
     * @return AuthMessage
     */
    private function getAuthMessage(ChallengeMessage $challenge)
    {
        $auth = $this->messageFactory->authenticate($this->session, $challenge);

        return $auth;
    }

    /**
     *
     * @param int $type Type of auth message
     * @return string
     */
    private function getHeader(Message $message)
    {
        return NTLMSSP::SIGNATURE . chr(0) . pack('V', $message->getType());
    }
}

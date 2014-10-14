<?php

namespace Aztech\Ntlm\Message;

use Aztech\Ntlm\Message;
use Aztech\Ntlm\NTLMSSP;
use Aztech\Ntlm\ServerChallenge;

class ChallengeMessage implements Message
{

    private $challenge;

    private $contextUpper;

    private $contextLower;

    public function __construct(ServerChallenge $challenge, $contextLower, $contextUpper)
    {
        $this->challenge = $challenge;
        $this->setContext($contextLower, $contextUpper);
    }

    public function getType()
    {
        return NTLMSSP::MSG_CHALLENGE;
    }

    public function getChallenge()
    {
        return $this->challenge;
    }

    public function getFlags()
    {
        return $this->challenge->getFlags();
    }

    public function getContextUpper()
    {
        return $this->contextUpper;
    }

    public function getContextLower()
    {
        return $this->contextLower;
    }

    public function setContext($lower, $upper)
    {
        $this->contextLower = $lower;
        $this->contextUpper = $upper;
    }

    public function getContent($offset)
    {
        throw new \BadMethodCallException('Not implemented.');
    }
}

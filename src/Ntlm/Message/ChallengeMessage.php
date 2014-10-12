<?php

namespace Aztech\Ntlm\Message;

use Aztech\Ntlm\Message;
use Aztech\Ntlm\NTLMSSP;

class ChallengeMessage implements Message
{

    private $target;

    private $flags;

    private $nonce;

    private $contextUpper;

    private $contextLower;

    public function __construct($target, $flags, $nonce, $contextUpper, $contextLower)
    {
        $this->target = $target;
        $this->flags = $flags;
        $this->nonce = $nonce;
        $this->contextUpper = $contextUpper;
        $this->contextLower = $contextLower;
    }

    public function getType()
    {
        return NTLMSSP::MSG_CHALLENGE;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function getFlags()
    {
        return $this->flags;
    }

    public function getNonce()
    {
        return $this->nonce;
    }

    public function getContextUpper()
    {
        return $this->contextUpper;
    }

    public function getContextLower()
    {
        return $this->contextLower;
    }

    public function getContent($offset)
    {
        throw new \BadMethodCallException('Not implemented.');
    }
}

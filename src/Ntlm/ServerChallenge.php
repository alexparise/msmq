<?php

namespace Aztech\Ntlm;

class ServerChallenge
{
    private $bytes;

    private $flags;

    private $targetName;

    public function __construct($nonce, $flags, $targetName)
    {
        $this->bytes = $nonce;
        $this->flags = $flags;
        $this->targetName = $targetName;
    }

    public function getChallengeBytes()
    {
        return $this->bytes;
    }

    public function getFlags()
    {
        return $this->flags;
    }

    public function getTargetName()
    {
        return $this->targetName;
    }
}

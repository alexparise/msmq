<?php

namespace Aztech\Ntlm;

class ServerChallengeResponse
{

    private $challenge;

    private $lmowfv1;

    private $ntowfv1;

    private $sessionKey;

    private $randomSessionKey;

    private $exchangeKey;

    public function __construct(ServerChallenge $challenge, $randomSessionKey, $lmowfv1, $ntowfv1)
    {
        $this->challenge = $challenge;

        $this->lmowfv1 = $lmowfv1;
        $this->ntowfv1 = $ntowfv1;

        $this->randomSessionKey = $randomSessionKey;
        $this->sessionKey       = NtlmHash::md4($this->ntowfv1);
        $this->exchangeKey      = $this->sessionKey;
    }

    /**
     *
     * @return ServerChallenge
     */
    public function getChallenge()
    {
        return $this->challenge;
    }

    public function getLmChallengeResponse()
    {
        if ($this->challenge->getFlags() & NTLMSSP::REQUEST_NON_NT_SESSION_KEY) {
            throw new \BadFunctionCallException('Unsupported flags.');
        }

        return NtlmHash::desl($this->lmowfv1, $this->challenge->getChallengeBytes());
    }

    public function getNtlmChallengeResponse()
    {
        return NtlmHash::desl($this->ntowfv1, $this->challenge->getChallengeBytes());
    }

    public function getSessionBaseKey()
    {
        return $this->sessionKey;
    }

    public function getEncryptedSessionKey()
    {
        return NtlmHash::rc4($this->exchangeKey, $this->randomSessionKey);
    }
}

<?php

namespace Aztech\Ntlm\Session;

use Aztech\Ntlm\ClientSession;
use Aztech\Ntlm\ServerChallenge;
use Aztech\Ntlm\Credentials;
use Aztech\Ntlm\NtlmHash;
use Aztech\Ntlm\ServerChallengeResponse;
use Aztech\Ntlm\NTLMSSP;

class NtlmClientSession implements ClientSession
{

    private $encryptedSessionKey;

    private $randomSessionKey;

    private $credentials;

    private $lmowfv1;

    private $ntowfv1;

    public function __construct(Credentials $credentials, $randomSessionKey)
    {
        $this->lmowfv1 = NtlmHash::lmowfv1($credentials->getPassword());
        $this->ntowfv1 = NtlmHash::ntowfv1($credentials->getPassword());

        $credentials->discardPassword();

        $this->credentials = $credentials;
        $this->randomSessionKey = $randomSessionKey;
    }

    public function getCredentials()
    {
        return $this->credentials;
    }

    public function getSessionKey()
    {
        return $this->encryptedSessionKey;
    }

    public function getNegotiationFlags()
    {
        return NTLMSSP::NEGOTIATE_KEY_EXCH
               | NTLMSSP::NEGOTIATE_128
               | NTLMSSP::NEGOTIATE_VERSION
               | NTLMSSP::TARGET_TYPE_SERVER
               | NTLMSSP::NEGOTIATE_ALWAYS_SIGN
               | NTLMSSP::NEGOTIATE_SEAL
               | NTLMSSP::NEGOTIATE_OEM
               | NTLMSSP::NEGOTIATE_UNICODE
               | NTLMSSP::NEGOTIATE_TARGET_INFO;
    }

    public function fork($randomSessionKey)
    {
        $fork = clone $this;
        $fork->randomSessionKey = $randomSessionKey;

        return $fork;
    }

    public function generateChallengeResponse(ServerChallenge $challenge)
    {
        $response = new ServerChallengeResponse(
            $challenge,
            $this->randomSessionKey,
            $this->lmowfv1,
            $this->ntowfv1
        );

        $this->encryptedSessionKey = $response->getEncryptedSessionKey();

        return $response;
    }
}

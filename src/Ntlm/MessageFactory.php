<?php

namespace Aztech\Ntlm;

use Aztech\Ntlm\Message\NegotiateMessage;
use Aztech\Ntlm\Message\ChallengeMessage;
use Aztech\Ntlm\Message\AuthMessage;

class MessageFactory
{
    public function negotiate($domain, $machine)
    {
        $flags = NTLMSSP::NEGOTIATE_80000000
               | NTLMSSP::NEGOTIATE_128
               | NTLMSSP::NEGOTIATE_ALWAYS_SIGN
               | NTLMSSP::NEGOTIATE_OEM_DOMAIN_SUPPLIED
               | NTLMSSP::NEGOTIATE_OEM_WORKSTATION_SUPPLIED
               | NTLMSSP::NEGOTIATE_NTLM
               | NTLMSSP::NEGOTIATE_UNICODE
               | NTLMSSP::NEGOTIATE_OEM
               | NTLMSSP::NEGOTIATE_KEY_EXCH
               | NTLMSSP::REQUEST_TARGET;

        return new NegotiateMessage($domain, $machine, $flags);
    }

    public function authenticate(ChallengeMessage $challenge, $user, $machine, $userDomain, $lmHash, $ntHash)
    {
        $flags = NTLMSSP::NEGOTIATE_ALWAYS_SIGN
               | NTLMSSP::NEGOTIATE_NTLM
               | NTLMSSP::NEGOTIATE_UNICODE
               | NTLMSSP::NEGOTIATE_KEY_EXCH
               | NTLMSSP::REQUEST_ACCEPT_RESPONSE
               | NTLMSSP::REQUEST_TARGET;

        $nonce = $challenge->getNonce();
        $auth = new AuthMessage($flags, $nonce, $userDomain, $user, $machine, $lmHash, $ntHash);

        return $auth;
    }
}

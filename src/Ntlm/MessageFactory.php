<?php

namespace Aztech\Ntlm;

use Aztech\Ntlm\Message\NegotiateMessage;
use Aztech\Ntlm\Message\ChallengeMessage;
use Aztech\Ntlm\Message\AuthMessage;
use Aztech\Util\Text;

class MessageFactory
{


    public function negotiate($callingDomain, $callingMachine)
    {
        $flags = NTLMSSP::NEGOTIATE_KEY_EXCH
               | NTLMSSP::NEGOTIATE_56
               | NTLMSSP::NEGOTIATE_128
               | NTLMSSP::NEGOTIATE_VERSION
               | NTLMSSP::TARGET_TYPE_SERVER
               | NTLMSSP::NEGOTIATE_ALWAYS_SIGN
               | NTLMSSP::NEGOTIATE_SEAL
               | NTLMSSP::NEGOTIATE_SIGN
               | NTLMSSP::NEGOTIATE_OEM
               | NTLMSSP::NEGOTIATE_UNICODE
               | NTLMSSP::NEGOTIATE_TARGET_INFO;

        return new NegotiateMessage($callingDomain, $callingMachine, $flags);
    }

    public function authenticate(RandomGenerator $generator, ChallengeMessage $challenge, Session $session, $password)
    {
        $flags = NTLMSSP::NEGOTIATE_KEY_EXCH
               | NTLMSSP::NEGOTIATE_56
               | NTLMSSP::NEGOTIATE_128
               | NTLMSSP::NEGOTIATE_VERSION
               | NTLMSSP::NEGOTIATE_ALWAYS_SIGN
               | NTLMSSP::NEGOTIATE_NTLM
               | NTLMSSP::NEGOTIATE_SEAL
               | NTLMSSP::NEGOTIATE_SIGN
               | NTLMSSP::NEGOTIATE_UNICODE
               | NTLMSSP::NEGOTIATE_TARGET_INFO
               | NTLMSSP::REQUEST_TARGET;

        $lmowfv1 = NtlmHash::lmowfv1($password);
        $ntowfv1 = NtlmHash::ntowfv1($password);
        $randomSessionKey = $generator->rand(16);

        $response = new ServerChallengeResponse($challenge->getChallenge(), $randomSessionKey, $lmowfv1, $ntowfv1);
        $auth = new AuthMessage($response, $session, $flags);

        return $auth;
    }
}

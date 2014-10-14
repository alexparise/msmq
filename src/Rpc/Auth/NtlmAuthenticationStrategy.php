<?php

namespace Aztech\Rpc\Auth;

use Aztech\Ntlm\Client;
use Aztech\Rpc\AuthenticationStrategy;
use Aztech\Rpc\PduType;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Rpc\Pdu\ConnectionOriented\BindAckPdu;

class NtlmAuthenticationStrategy implements AuthenticationStrategy
{

    private $client = null;

    private $verifier = null;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getVerifier($type, AuthenticationContext $context, ProtocolDataUnit $lastResponse = null)
    {
        if ($type == PduType::BIND) {
            return new NtlmNegotiateVerifier($this->client, $context);
        }
        elseif ($type == PduType::BIND_RESP && $lastResponse instanceof BindAckPdu) {
            $challengeData = $lastResponse->getRawAuthData();
            $challenge = $this->client->parseChallenge($challengeData);
            $challengeResponse = $this->client->getAuthPacket($challenge);
            
            return new NtlmChallengeResponseVerifier($context, $challengeResponse);
        }
        
        if ($lastResponse && ! $this->verifier) {
            $this->verifier = new NtlmVerifier($context, $this->client->getSessionKey());
        }
        
        return $this->verifier;
    }

    public function getType()
    {
        return 0x0a;
    }
}

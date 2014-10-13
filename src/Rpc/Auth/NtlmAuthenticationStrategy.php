<?php

namespace Aztech\Rpc\Auth;

use Aztech\Rpc\AuthenticationStrategy;
use Aztech\Ntlm\Client;
use Aztech\Rpc\ProtocolDataUnit;

class NtlmAuthenticationStrategy implements AuthenticationStrategy
{
    private $step = 0;

    private $client = null;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getVerifier($type, AuthenticationContext $context, ProtocolDataUnit $lastResponse = null)
    {
        if ($this->step == 0) {
            return new NtlmNegotiateVerifier($this->client, $context);
        }
    }

    public function getType()
    {
        return 0x0a;
    }
}

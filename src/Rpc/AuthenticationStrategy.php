<?php

namespace Aztech\Rpc;

use Aztech\Rpc\Auth\AuthenticationContext;

interface AuthenticationStrategy
{
    public function getVerifier($type, AuthenticationContext $context, ProtocolDataUnit $lastResponse = null);

    public function getType();
}

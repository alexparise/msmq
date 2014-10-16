<?php

namespace Aztech\Rpc\Auth;

use Aztech\Rpc\ProtocolDataUnit;

interface AuthenticationStrategy
{
    public function getVerifier($type, AuthenticationContext $context, ProtocolDataUnit $lastResponse = null);

    public function getType();
}

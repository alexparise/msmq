<?php

namespace Aztech\Rpc\Auth;

interface AuthenticationParser
{
    /**
     *
     * @param string $bytes
     * @return AuthenticationVerifier
     */
    public function parse($pduType, $bytes);
}

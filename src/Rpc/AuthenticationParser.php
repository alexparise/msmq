<?php

namespace Aztech\Rpc;

interface AuthenticationParser
{
    /**
     * 
     * @param string $bytes
     * @return AuthenticationVerifier
     */
    public function parse($pduType, $bytes);
}
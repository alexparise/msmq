<?php

namespace Aztech\Rpc\Auth;

use Aztech\Rpc\AuthenticationVerifier;

class NtlmVerifier implements AuthenticationVerifier
{

    public function getVersion()
    {
        return 1;
    }

    public function getContent()
    {
        return "";
    }
}

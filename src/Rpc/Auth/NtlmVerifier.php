<?php

namespace Aztech\Rpc\Auth;

use Aztech\Rpc\AuthenticationVerifier;
use Aztech\Rpc\PduFieldCollection;

class NtlmVerifier implements AuthenticationVerifier
{
    public function getFields()
    {
        return new PduFieldCollection();
    }
}

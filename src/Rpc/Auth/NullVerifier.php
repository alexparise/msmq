<?php

namespace Aztech\Rpc\Auth;

use Aztech\Rpc\AuthenticationVerifier;
use Aztech\Rpc\PduFieldCollection;
use Aztech\Net\DataTypes;
use Aztech\Rpc\ProtocolDataUnit;
use Aztech\Ntlm\Session;

class NullVerifier implements AuthenticationVerifier
{

    public function getSize($padding)
    {
        return 0;
    }

    public function getHeaders($padding)
    {
        return new PduFieldCollection();
    }

    public function getContent()
    {
        return new PduFieldCollection();
    }

    public function seal(ProtocolDataUnit $pdu)
    {
        return $pdu;
    }

    public function sign(ProtocolDataUnit $pdu)
    {
        return $pdu;
    }
}

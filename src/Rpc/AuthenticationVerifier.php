<?php

namespace Aztech\Rpc;

interface AuthenticationVerifier
{

    public function getContent();

    public function sign(ProtocolDataUnit $pdu);
    
    public function seal(ProtocolDataUnit $pdu);
}

<?php

namespace Aztech\Rpc;

interface AuthenticationVerifier
{
    public function getVersion();

    public function getContent();

    public function sign(ProtocolDataUnit $pdu);
}

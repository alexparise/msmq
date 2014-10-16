<?php

namespace Aztech\Rpc\Auth;

use Aztech\Rpc\ProtocolDataUnit;

interface AuthenticationVerifier
{

    /**
     * @return PduFieldCollection
     */
    public function getHeaders($padding);

    /**
     * @return PduFieldCollection
     */
    public function getContent();

    public function sign(ProtocolDataUnit $pdu);

    public function seal(ProtocolDataUnit $pdu);
}

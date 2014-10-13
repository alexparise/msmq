<?php

namespace Aztech\Rpc;

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

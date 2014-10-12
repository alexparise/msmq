<?php

namespace Aztech\Rpc;

interface ProtocolDataUnit
{

    const PFC_FIRST_FRAG        = 0x01;

    const PFC_LAST_FRAG         = 0x02;

    const PFC_PENDING_CANCEL    = 0x04;

    const PFC_RESERVED_1        = 0x08;

    const PFC_CONC_MPX          = 0x10;

    const PFC_DID_NOT_EXECUTE   = 0x20;

    const PFC_MAYBE             = 0x40;

    const PFC_OBJECT_UUID       = 0x80;

    /**
     *
     * @return PduFieldCollection
     */
    public function getHeaders();

    /**
     *
     * @return string
     */
    public function getBody();

    /**
     *
     * @return AuthenticationVerifier
     */
    public function getVerifier();
}

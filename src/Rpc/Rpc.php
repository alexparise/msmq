<?php

namespace Aztech\Rpc;

class Rpc
{
    const VERSION_MAJOR         = 5;

    const VERSION_MINOR         = 0;

    const REQUEST               = 0x00;

    const PING                  = 0x01;

    const RESPONSE              = 0x02;

    const FAULT                 = 0x03;

    const WORKING               = 0x04;

    const NOCALL                = 0x05;

    const REJECT                = 0x06;

    const ACK                   = 0x07;

    const CL_CANCEL             = 0x08;

    const FACK                  = 0x09;

    const CANCEL_ACK            = 0x0a;

    const BIND                  = 0x0b;

    const BIND_ACK              = 0x0c;

    const BIND_NACK             = 0x0d;

    const ALTER_CONTEXT         = 0x0e;

    const ALTER_CONTEXT_RESP    = 0x0f;

    const BIND_RESP             = 0x10;

    const SHUTDOWN              = 0x11;

    const CO_CANCEL             = 0x12;

    const PFC_FIRST_FRAG        = 0x01;

    const PFC_LAST_FRAG         = 0x02;

    const PFC_PENDING_CANCEL    = 0x04;

    const PFC_RESERVED_1        = 0x08;

    const PFC_CONC_MPX          = 0x10;

    const PFC_DID_NOT_EXECUTE   = 0x20;

    const PFC_MAYBE             = 0x40;

    const PFC_OBJECT_UUID       = 0x80;

    const CO_HDR_SZ             = 16;

    const CO_RESP_HDR_SZ        = 8;

    const FRAG_SZ               = 5840;

    const AUTH_HDR_SZ           = 8;

    const AUTH_NTLM             = 0x0a;

    const AUTH_LEVEL_CONNECT    = 0x02;
}

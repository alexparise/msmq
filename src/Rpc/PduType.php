<?php

namespace Aztech\Rpc;

interface PduType
{
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
   
}

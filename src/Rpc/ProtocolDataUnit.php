<?php

namespace Aztech\Rpc;

interface ProtocolDataUnit extends WriteVisitable
{

    const TYPE_REQUEST              = 0x00;

    const TYPE_PING                 = 0x01;

    const TYPE_RESPONSE             = 0x02;

    const TYPE_FAULT                = 0x03;

    const TYPE_WORKING              = 0x04;

    const TYPE_NOCALL               = 0x05;

    const TYPE_REJECT               = 0x06;

    const TYPE_ACK                  = 0x07;

    const TYPE_CL_CANCEL            = 0x08;

    const TYPE_FACK                 = 0x09;

    const TYPE_CANCEL_ACK           = 0x0a;

    const TYPE_BIND                 = 0x0b;

    const TYPE_BIND_ACK             = 0x0c;

    const TYPE_BIND_NACK            = 0x0d;

    const TYPE_ALTER_CONTEXT        = 0x0e;

    const TYPE_ALTER_CONTEXT_RESP   = 0x0f;

    const TYPE_SHUTDOWN             = 0x11;

    const TYPE_CO_CANCEL            = 0x12;

    const TYPE_ORPHANED             = 0x13;

    public function getType();

    public function getHeader();

    public function hasBody();

    public function getBody();

    public function hasAuthVerifier();

    public function getAuthVerifier();
}

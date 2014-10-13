<?php

namespace Aztech\Rpc;

use Aztech\Rpc\Pdu\ConnectionOriented\BindPdu;
use Aztech\Rpc\Pdu\ConnectionOriented\BindAckPdu;
use Aztech\Rpc\Pdu\ConnectionOriented\BindResponsePdu;

interface ProtocolDataUnitVisitor
{

    public function visitBind(BindPdu $pdu);

    public function visitBindAck(BindAckPdu $pdu);

    public function visitBindResponse(BindResponsePdu $pdu);

}

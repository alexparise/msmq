<?php

namespace Aztech\Rpc;

use Aztech\Rpc\Pdu\ConnectionOriented\BindPdu;
use Aztech\Rpc\Pdu\ConnectionOriented\BindAckPdu;
use Aztech\Rpc\Pdu\ConnectionOriented\BindResponsePdu;
use Aztech\Rpc\Pdu\ConnectionOriented\RequestPdu;

interface ProtocolDataUnitVisitor
{

    public function visitBind(BindPdu $pdu);

    public function visitBindAck(BindAckPdu $pdu);

    public function visitBindResponse(BindResponsePdu $pdu);
    
    public function visitRequest(RequestPdu $pdu);

}

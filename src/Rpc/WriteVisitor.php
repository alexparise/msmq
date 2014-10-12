<?php

namespace Aztech\Rpc;

use Aztech\Rpc\Pdu\DataRepresentationFormat;

interface WriteVisitor
{
    public function visit(WriteVisitable $visitable);

    public function visitPdu(ProtocolDataUnit $pdu);

    public function visitHeader(ProtocolDataUnitHeader $header);

    public function visitBody(ProtocolDataUnitBody $body);

    public function visitDataRepresentationFormat(DataRepresentationFormat $format);
}

<?php

namespace Aztech\Dcom\Marshalling\Marshaller;

use Aztech\Dcom\Marshalling\Marshaller;
use Aztech\Net\Reader;
use Aztech\Net\Writer;
use Aztech\Dcom\Floor;

class FloorMarshaller implements Marshaller
{
    public function marshall(Writer $writer, $value)
    {

    }

    public function unmarshallNext(Reader $reader)
    {
        $lhsLen = $reader->readUInt16();

        if ($lhsLen == 0) {
            throw new \RuntimeException();
        }

        $lhs = bin2hex($reader->read($lhsLen));
        $proto = hexdec(substr($lhs, 0, 2));

        $rhsLen = $reader->readUInt16();
        $rhs = "";
        if ($rhsLen > 0) {
            $rhs = bin2hex($reader->read($rhsLen));
        }

        return new Floor($proto, $lhsLen, $rhsLen, $lhs, $rhs);
    }
}

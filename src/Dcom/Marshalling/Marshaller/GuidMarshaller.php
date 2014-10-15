<?php

namespace Aztech\Dcom\Marshalling\Marshaller;

use Aztech\Dcom\Marshalling\Marshaller;
use Aztech\Net\Reader;
use Rhumsaa\Uuid\Uuid;
use Aztech\Net\Writer;

class GuidMarshaller implements Marshaller
{
    public function marshall(Writer $writer, $value)
    {
        if ($value == null) {
            $value = Uuid::fromBytes(hex2bin("00000000000000000000000000000000"));
        }

        $writer->write($value->getBytes());
    }

    public function unmarshallNext(Reader $reader)
    {
        throw new \BadMethodCallException();
    }
}

<?php

namespace Aztech\Dcom\Marshalling\Marshaller;

use Aztech\Net\Writer;
use Aztech\Net\Reader;
use Aztech\Dcom\Marshalling\Marshaller;
use Aztech\Dcom\ObjRef;

class ObjRefMarshaller implements Marshaller
{
    public function marshall(Writer $writer, $value)
    {
        /* @var $value \Aztech\Dcom\ObjRef */
        $writer->writeUInt32(0x574f454d);
        $writer->writeUInt32($value->getFlags());
        $writer->write($value->getIid()->getBytes());

        switch ($value->getFlags()) {
            case ObjRef::OBJREF_STANDARD:
            case ObjRef::OBJREF_HANDLER:
            case ObjRef::OBJREF_CUSTOM:
            case ObjRef::OBJREF_EXTENDED:
                $write->write('OBJREF');
        }
    }

    public function unmarshallNext(Reader $reader)
    {
        throw new \BadMethodCallException();
    }
}

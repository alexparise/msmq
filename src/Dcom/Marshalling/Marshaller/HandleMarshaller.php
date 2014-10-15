<?php

namespace Aztech\Dcom\Marshalling\Marshaller;

use Aztech\Dcom\Marshalling\Marshaller;
use Aztech\Net\Writer;
use Aztech\Net\Reader;
use Rhumsaa\Uuid\Uuid;
use Aztech\Dcom\Handle;

class HandleMarshaller implements Marshaller
{
    public function marshall(Writer $writer, $value)
    {
        $writer->writeUInt32($value->getAttributes());
        $writer->write($value->getUuid()->getBytes());
    }

    public function unmarshallNext(Reader $reader)
    {
        $attributes = $reader->readUInt32();
        $uuid = Uuid::fromBytes($reader->read(16));

        return new Handle($attributes, $uuid);
    }
}

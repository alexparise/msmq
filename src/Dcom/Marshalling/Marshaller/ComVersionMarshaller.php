<?php

namespace Aztech\Dcom\Marshalling\Marshaller;

use Aztech\Dcom\ComVersion;
use Aztech\Dcom\Marshalling\Marshaller;
use Aztech\Net\Reader;
use Aztech\Net\Writer;

class ComVersionMarshaller implements Marshaller
{

    public function marshall(Writer $writer, $value)
    {
        throw new \BadMethodCallException();
    }

    public function unmarshallNext(Reader $reader)
    {
        $major = $reader->readUInt16();
        $minor = $reader->readUInt16();

        return new ComVersion($major, $minor);
    }
}

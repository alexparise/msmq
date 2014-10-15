<?php

namespace Aztech\Dcom\Marshalling\Marshaller;

use Aztech\Dcom\Marshalling\Marshaller;
use Aztech\Net\Writer;
use Aztech\Net\Reader;

class OrcpThisMarshaller implements Marshaller
{
    public function marshall(Writer $writer, $value)
    {
        $writer->write($value->getBytes());
    }

    public function unmarshallNext(Reader $reader)
    {
        throw new \BadMethodCallException();
    }
    }

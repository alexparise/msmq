<?php

namespace Aztech\Dcom\Marshalling\Marshaller;

use Aztech\Dcom\Marshalling\Marshaller;
use Aztech\Net\Reader;
use Aztech\Net\Writer;

class PointerMarshaller implements Marshaller
{
    private $marshaller;

    public function __construct(Marshaller $marshaller)
    {
        $this->marshaller = $marshaller;
    }

    public function marshall(Writer $writer, $value)
    {
        $writer->writeUInt32($value->getRefId());
        $this->marshaller->marshall($writer, $value->getValue());
    }

    public function unmarshallNext(Reader $reader)
    {

    }
}

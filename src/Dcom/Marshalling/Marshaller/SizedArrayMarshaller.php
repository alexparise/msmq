<?php

namespace Aztech\Dcom\Marshalling\Marshaller;

use Aztech\Dcom\Marshalling\Marshaller;
use Aztech\Net\Reader;
use Aztech\Net\Writer;

class SizedArrayMarshaller implements Marshaller
{
    private $marshaller;

    public function __construct(Marshaller $marshaller)
    {
        $this->marshaller = $marshaller;
    }

    public function marshall(Writer $writer, $value)
    {
        $writer->writeUInt32(count($value));

        $this->marshaller->marshall($writer, $value);
    }

    public function unmarshallNext(Reader $reader)
    {

    }
}

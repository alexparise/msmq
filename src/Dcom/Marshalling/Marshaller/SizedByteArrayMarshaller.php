<?php

namespace Aztech\Dcom\Marshalling\Marshaller;

use Aztech\Dcom\Marshalling\Marshaller;
use Aztech\Net\Reader;
use Aztech\Net\Writer;

/**
 * Marshalls binary arrays (stored in binary strings)
 *
 * @author thibaud
 *
 */
class SizedByteArrayMarshaller implements Marshaller
{
    public function marshall(Writer $writer, $value)
    {
        $size = strlen($value);

        $writer->writeUInt32($size);
        $writer->write($value);
    }

    public function unmarshallNext(Reader $reader)
    {

    }
}

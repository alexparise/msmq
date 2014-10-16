<?php

namespace Aztech\Dcom\Marshalling\Marshaller;

use Aztech\Dcom\Marshalling\Marshaller;
use Aztech\Net\Reader;
use Aztech\Net\Writer;
use Aztech\Util\Text;

class UnicodeStringMarshaller implements Marshaller
{

    public function marshall(Writer $writer, $value)
    {
        $writer->writeUInt32(strlen($value));
        $writer->writeUInt32(0);
        $writer->writeUInt32(strlen($value));

        $value = Text::toUnicode($value);
        $writer->write($value);
    }

    public function unmarshallNext(Reader $reader)
    {

    }
}

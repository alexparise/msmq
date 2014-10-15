<?php

namespace Aztech\Dcom\Marshalling\Marshaller;

use Aztech\Dcom\Marshalling\Marshaller;
use Aztech\Net\Reader;
use Rhumsaa\Uuid\Uuid;
use Aztech\Net\Writer;
use Aztech\Util\Guid;

class GuidMarshaller implements Marshaller
{
    public function marshall(Writer $writer, $value)
    {
        if (is_array($value)) {
            foreach ($value as $v) {
                $this->marshall($writer, $v);
            }

            return;
        }

        if ($value == null) {
            $value = Guid::null();
        }

        $writer->write($value->getBytes());
    }

    public function unmarshallNext(Reader $reader)
    {
        $bytes = $reader->read(16);

        if (! $bytes) {
            return Guid::null();
        }

        return Uuid::fromBytes($bytes);
    }
}

<?php

namespace Aztech\Dcom\Marshalling\Marshaller;

use Aztech\Dcom\Marshalling\Marshaller;
use Aztech\Net\Reader;
use Aztech\Net\DataTypes;
use Aztech\Util\Text;
use Aztech\Dcom\Marshalling\SecurityBinding;
use Aztech\Net\Writer;
use Aztech\Dcom\Marshalling\StringBinding;
use Aztech\Dcom\Marshalling\DualStringArray;

class DualStringArrayMarshaller implements Marshaller
{

    public function marshall(Writer $writer, $value)
    {
        throw new \BadMethodCallException();
    }

    public function unmarshallNext(Reader $reader)
    {
        $numEntries = $reader->readUInt16();
        $securityOffset = $reader->readUInt16();

        $offset = $reader->getReadByteCount();

        $stringBindings = [];
        $securityBindings = [];

        while (ceil(($reader->getReadByteCount() - $offset + 1) / DataTypes::INT16_SZ) < $securityOffset) {
            $towerId = $reader->readUInt16();
            $networkAddress = Text::fromUnicode($reader->readTo("0000", true));

            $stringBindings[] = new StringBinding($networkAddress, $towerId);
        }

        $reader->read(2);

        while (ceil(($reader->getReadByteCount() - $offset + 1) / DataTypes::INT16_SZ) < $numEntries) {
            $authnSvc = $reader->readUInt16();
            $authzSvc = $reader->readUInt16();
            $principalName = $reader->readTo("0000");

            $securityBindings[] = new SecurityBinding($authnSvc, $authzSvc, $principalName);
        }

        return new DualStringArray($stringBindings, $securityBindings);
    }
}

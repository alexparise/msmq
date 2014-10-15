<?php

namespace Aztech\Dcom\Marshalling\Marshaller;

use Aztech\Dcom\Marshalling\Marshaller;
use Aztech\Net\Writer;
use Aztech\Net\Reader;
use Rhumsaa\Uuid\Uuid;
use Aztech\Dcom\Handle;
use Aztech\Dcom\TowerPointer;
use Aztech\Dcom\Tower;
use Aztech\Net\Buffer\BufferReader;

class TowerMarshaller implements Marshaller
{

    private $floorMarshall;

    public function __construct(Marshaller $floorMarshall = null)
    {
        $this->floorMarshall = $floorMarshall ?: new FloorMarshaller();
    }

    public function marshall(Writer $writer, $value)
    {
        throw new \BadMethodCallException();
    }

    public function unmarshallNext(Reader $reader)
    {
        static $z = 0;

        $len = $reader->readUInt32();
        $len = $reader->readUInt32();
        $flc = $reader->readUInt16();

        if ($len == 0) {
            return null;
        }

        $floors = [];

        for ($i = 0; $i < $flc; $i++) {
            $floors[] = $this->floorMarshall->unmarshallNext($reader);
        }

        return new Tower($floors);
    }
}

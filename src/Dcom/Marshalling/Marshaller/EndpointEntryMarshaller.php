<?php

namespace Aztech\Dcom\Marshalling\Marshaller;

use Aztech\Dcom\Marshalling\Marshaller;
use Aztech\Net\Reader;
use Aztech\Net\Writer;
use Aztech\Dcom\EndpointEntry;

class EndpointEntryMarshaller implements Marshaller
{
    public function marshall(Writer $writer, $value)
    {
        throw new \BadMethodCallException();
    }

    public function unmarshallNext(Reader $reader)
    {
        $numEntries = $reader->readUInt32();
        $maxCount = $reader->readUInt32();
        $offset = $reader->readUInt32();
        $actualCount = $reader->readUInt32();

        $guidMarshall = new GuidMarshaller();
        $towerMarshall = new TowerMarshaller();

        $entries = [];

        for ($i = 0; $i < $actualCount; $i++) {
            $object = $guidMarshall->unmarshallNext($reader);
            $towerId = $reader->readUInt32();
            $annotationOffset = $reader->readUInt32();
            $annotationLength = $reader->readUInt32();

            if ($annotationOffset > 0) {
                $reader->read($annotationOffset);
            }

            $annotation = ($annotationLength > 0) ? $reader->read($annotationLength) : "";
            $entry = new EndpointEntry($object, $towerId, $annotation);
            $entries[] = $entry;

            $this->alignReader($reader);
        }

        $i = 0;

        foreach ($entries as $entry) {
            $entry->setTower($towerMarshall->unmarshallNext($reader));
            $this->alignReader($reader);
        }

        return $entries;
    }

    private function alignReader(Reader $reader) {
        while ($reader->getReadByteCount() % 4 != 0) {
            $reader->read(1);
        }
    }
}

<?php

namespace Aztech\Dcom\Marshalling;

use Aztech\Net\Reader;
use Aztech\Net\Writer;
use Aztech\Util\Text;

class MarshallMap
{
    /**
     *
     * @var Marshaller[]
     */
    private $marshallers = [];

    /**
     *
     * @var int[]
     */
    private $offsets = [];

    public function add($marshaller, $offset = 0)
    {
        if ($marshaller instanceof Marshaller) {
            $index = count($this->marshallers);

            $this->marshallers[$index] = $marshaller;
            $this->offsets[$index] = $offset;
        }
    }

    public function writeValues(Writer $writer, array $values)
    {
        $i = 0;

        for ($i = 0; $i < count($values); $i++) {
            if ($this->offsets[$i] > 0) {
                $writer->write(hex2bin(str_pad("", "0", $this->offsets[$i] * 2)));
            }

            $this->marshallers[$i]->marshall($writer, $values[$i]);
        }
    }

    public function extractValues(Reader $reader)
    {
        $values = [];

        for ($i = 0; $i < count($this->marshallers); $i++) {
            if ($this->offsets[$i] > 0) {
                $reader->read($this->offsets[$i]);
            }

            $value = $this->marshallers[$i]->unmarshallNext($reader);

            $values[] = $value;
        }

        return $values;
    }
}

<?php

namespace Aztech\Dcom\Marshalling;

use Aztech\Net\Writer;
use Aztech\Net\Reader;

class UnmarshallingBuffer
{

    private $marshallMap;

    private $values;

    public function __construct()
    {
        $this->marshallMap = new MarshallMap();
    }

    public function add($marshaller, $offset = 0)
    {
        return $this->marshallMap->add($marshaller, $offset);
    }

    public function getMap()
    {
        return $this->marshallMap;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function parseValues(Reader $reader)
    {
        $this->values = $this->marshallMap->extractValues($reader);
    }
}

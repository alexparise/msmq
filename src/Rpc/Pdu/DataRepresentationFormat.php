<?php

namespace Aztech\Rpc\Pdu;

use Aztech\Rpc\WriteVisitable;
use Aztech\Rpc\WriteVisitor;

class DataRepresentationFormat implements WriteVisitable
{
    const CHR_ASCII         = 0x0;

    const CHR_EBCDIC        = 0x1;

    const INT_BIG_ENDIAN    = 0x0;

    const INT_LITTLE_ENDIAN = 0x1;

    const FLOAT_IEEE        = 0x00;

    const FLOAT_VAX         = 0x01;

    const FLOAT_CRAY        = 0x02;

    const FLOAT_IBM         = 0x03;

    private $charType = self::CHR_ASCII;

    private $intType = self::INT_LITTLE_ENDIAN;

    private $floatType = self::FLOAT_IEEE;

    public function __construct($charType = self::CHR_ASCII, $intType = self::INT_LITTLE_ENDIAN, $floatType = self::FLOAT_IEEE)
    {
        $this->validate('Character representation', $charType, 0x0, 0x1);
        $this->validate('Integer representation', $intType, 0x0, 0x1);
        $this->validate('Float representation', $floatType, 0x0, 0x3);

        $this->charType = $charType;
        $this->intType = $intType;
        $this->floatType = $floatType;
    }

    public function getCharType()
    {
        return $this->charType;
    }

    public function getIntType()
    {
        return $this->intTYpe;
    }

    public function getFloatType()
    {
        return $this->floatType;
    }

    private function validate($typeName, $typeValue, $minValue, $maxValue)
    {
        if ($typeValue < $minValue || $typeValue > $maxValue) {
            throw new \InvalidArgumentException($typeName . ' is invalid.');
        }
    }

    public function accept(WriteVisitor $writer)
    {
        return $writer->visitDataRepresentationFormat($this);
    }
}

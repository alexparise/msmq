<?php

namespace Aztech\Dcom\Marshalling\Marshaller;

use Aztech\Dcom\Marshalling\Marshaller;
use Aztech\Net\Writer;
use Aztech\Net\Reader;
use Aztech\Net\DataTypes;
use Aztech\Net\ByteOrder;

class PrimitiveMarshaller implements Marshaller
{

    public static function Int64()
    {
        return new self(DataTypes::UINT64);
    }

    public static function Int32()
    {
        return new self(DataTypes::UINT32);
    }

    public static function Int16()
    {
        return new self(DataTypes::UINT16);
    }

    public static function UInt64()
    {
        return new self(DataTypes::UINT64);
    }

    public static function UInt32()
    {
        return new self(DataTypes::UINT32);
    }

    public static function UInt16()
    {
        return new self(DataTypes::UINT16);
    }

    private $type = 0;

    public function __construct($dataType)
    {
        $this->type = $dataType;
    }

    public function marshall(Writer $writer, $value)
    {
        if (is_array($value)) {
            foreach ($value as $v) {
                $this->marshall($writer, $v);
            }

            return;
        }

        switch ($this->type) {
            case DataTypes::INT8:
                return $writer->writeInt8($value);
            case DataTypes::INT16:
                return $writer->writeInt16($value);
            case DataTypes::INT32:
                return $writer->writeInt32($value);
            case DataTypes::INT64:
                return $writer->writeInt64($value);
            case DataTypes::UINT8:
                return $writer->writeUInt8($value);
            case DataTypes::UINT16:
                return $writer->writeUInt16($value);
            case DataTypes::UINT32:
                return $writer->writeUInt32($value);
            case DataTypes::UINT64:
                return $writer->writeUInt64($value);
            case DataTypes::BYTES:
            default:
                return $writer->write($value);
        }
    }

    public function unmarshallNext(Reader $reader)
    {
        switch ($this->type) {
            case DataTypes::INT8:
                return $reader->readInt8();
            case DataTypes::INT16:
                return $reader->readInt16();
            case DataTypes::INT32:
                return $reader->readInt32();
            case DataTypes::INT64:
                return $reader->readInt64();
            case DataTypes::UINT8:
                return $reader->readUInt8();
            case DataTypes::UINT16:
                return $reader->readUInt16();
            case DataTypes::UINT32:
                return $reader->readUInt32();
            case DataTypes::UINT64:
                return $reader->readUInt64();
            case DataTypes::BYTES:
            default:
                throw new \BadMethodCallException();
        }
    }
}

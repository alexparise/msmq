<?php

namespace Aztech\Rpc;

use Aztech\Net\DataTypes;

class PduFieldCollection
{

    private $fields = [];

    public function addField($type, $value)
    {
        if ($value instanceof PduFieldCollection) {
            throw new \InvalidArgumentException();
        }

        $this->fields[] = new PduFieldDefinition($type, $value);

        return $this;
    }

    /**
     *
     * @return PduFieldDefinition[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    public function getSize()
    {
        $size = 0;

        foreach ($this->fields as $field) {
            switch ($field->getType()) {
                case DataTypes::INT8:
                case DataTypes::UINT8:
                    $size += DataTypes::INT8_SZ;
                    break;
                case DataTypes::INT16:
                case DataTypes::UINT16:
                    $size += DataTypes::INT16_SZ;
                    break;
                case DataTypes::INT32:
                case DataTypes::UINT32:
                    $size += DataTypes::INT32_SZ;
                    break;
                case DataTypes::INT64:
                case DataTypes::UINT64:
                    $size += DataTypes::INT64_SZ;
                    break;
                case DataTypes::BYTES:
                default:
                    $size += strlen($field->getValue());
            }
        }

        return $size;
    }
}

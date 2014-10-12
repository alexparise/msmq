<?php

namespace Aztech\Rpc;

class PduFieldCollection
{
    private $fields = [];

    public function addField($type, $value)
    {
        $this->fields[] = new \PduFieldDefinition($type, $value);

        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }
}

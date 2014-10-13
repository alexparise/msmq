<?php

namespace Aztech\Rpc;

class PduFieldDefinition
{

    private $type;

    private $value;

    public function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getValue()
    {
        if (is_callable($this->value)) {
            return call_user_func($this->value);
        }

        return $this->value;
    }
}

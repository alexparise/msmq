<?php

namespace Aztech\Dcom\Marshalling;

class DualStringArray
{

    private $stringBindings;

    private $securityBindings;

    public function __construct(array $stringBindings, array $securityBindings)
    {
        $this->stringBindings = $stringBindings;
        $this->securityBindings = $securityBindings;
    }

    public function getStringBindings()
    {
        return $this->stringBindings;
    }

    public function getSecurityBindings()
    {
        return $this->securityBindings;
    }
}

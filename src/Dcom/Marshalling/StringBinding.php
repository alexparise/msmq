<?php

namespace Aztech\Dcom\Marshalling;

class StringBinding
{

    private $towerId;

    private $address;

    public function __construct($address, $towerId)
    {
        $this->address = $address;
        $this->port = $towerId;
    }

    public function getNetworkAddress()
    {
        return $this->address;
    }

    public function getTowerId()
    {
        return $this->towerId;
    }
}

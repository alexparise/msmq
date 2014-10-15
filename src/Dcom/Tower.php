<?php

namespace Aztech\Dcom;

class Tower
{

    private $floors;

    public function __construct(array $floors)
    {
        $this->floors = $floors;
    }

    public function getFloors()
    {
        return $this->floors;
    }
}

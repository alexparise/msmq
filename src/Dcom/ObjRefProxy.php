<?php

namespace Aztech\Dcom;

class ObjRefProxy
{
    
    private $interface;
    
    private $objRef;
    
    public function __construct(DcomInterface $interface, ObjRef $ref)
    {
        $this->interface = $interface;
        $this->objRef = $objRef;
    }
}
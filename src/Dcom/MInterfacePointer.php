<?php

namespace Aztech\Dcom;

class MInterfacePointer
{
    private $size;
    
    private $data;
    
    public function __construct(ObjRef $objRef)
    {
        $this->objRef = $objRef;
    }
    
    public function getObjRef()
    {
        return $this->objRef;
    }
}
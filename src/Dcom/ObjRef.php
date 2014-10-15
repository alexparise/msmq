<?php

namespace Aztech\Dcom;

abstract class ObjRef
{
    const OBJREF_STANDARD = 0x00000001;
    
    const OBJREF_HANDLER = 0x00000002;
    
    const OBJREF_CUSTOM = 0x00000004;
    
    const OBJREF_EXTENDED = 0x00000008;
    
    private $flags;
    
    private $iid;
    
    public function __construct(Uuid $iid, $flags)
    {
        $this->iid = $iid;
        $this->flags = $flags;
    }
    
    public function getFlags()
    {
        return $this->flags;
    }
    
    public function getIid()
    {
        return $this->iid;
    }
}
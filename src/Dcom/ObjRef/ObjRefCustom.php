<?php

namespace Aztech\Dcom\ObjRef;

use Aztech\Dcom\ObjRef;
use Rhumsaa\Uuid\Uuid;

class ObjRefCustom extends ObjRef
{
    private $unmarshallClsId;
    
    private $extensionData = "";
    
    public function __construct(Uuid $unmarshallClsid, Uuid $iid)
    {
        parent::__construct($iid, self::OBJREF_CUSTOM);
        
        $this->unmarshallClsId = $unmarshallClsid;
    }
    
    public function getExtensionData()
    {
        return $this->extensionData;
    }
    
    public function getUnmarshallClsId()
    {
        return $this->unmarshallClsId;
    }
}
<?php

namespace Aztech\Dcom\ObjRef;

use Aztech\Dcom\ObjRef;
use Rhumsaa\Uuid\Uuid;

class ObjRefStandard extends ObjRef
{

    public function __construct(Uuid $iid)
    {
        parent::__construct($iid, ObjRef::OBJREF_STANDARD);
    }
}

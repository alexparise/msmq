<?php

namespace Aztech\Com;

class IRemoteActivation implements DcomInterface
{
    public function getIid()
    {
        return pack('H32', 'B84A9F4D1C7DCF11861E0020AF6E7C57');
    }
    
    public function getVersion()
    {
        return 0x00;
    }
}
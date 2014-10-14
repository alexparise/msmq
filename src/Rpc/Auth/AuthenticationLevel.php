<?php

namespace Aztech\Rpc\Auth;

class AuthenticationLevel
{

    const NONE = 0x00;

    const ANONYMOUS = 0x01;

    const CONNECT = 0x02;
    
    const CALL = 0x03;
    
    const PACKET = 0x04;
    
    const INTEGRITY = 0x05;
    
    const PRIVACY = 0x06;

}

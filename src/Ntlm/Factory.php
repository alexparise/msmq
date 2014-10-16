<?php

namespace Aztech\Ntlm;

use Aztech\Ntlm\Session\NtlmClientSession;
use Aztech\Ntlm\Random\OpenSslGenerator;

class Factory
{

    public static function ntlmV1($user, $password, $domain = '', $workstation = '')
    {
        $credentials = new Credentials($user, $password, $domain, $workstation);
        $generator = new OpenSslGenerator();
        $session = new NtlmClientSession($credentials, $generator->rand(16));

        return new Client($session);
    }
}

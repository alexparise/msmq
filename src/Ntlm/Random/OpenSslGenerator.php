<?php

namespace Aztech\Ntlm\Random;

use Aztech\Ntlm\RandomGenerator;

class OpenSslGenerator implements RandomGenerator
{
    public function rand($bytes)
    {
        return openssl_random_pseudo_bytes($bytes);
    }
}

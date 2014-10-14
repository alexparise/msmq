<?php

namespace Aztech\Ntlm\Random;

use Aztech\Ntlm\RandomGenerator;

class StaticGenerator implements RandomGenerator
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function rand($bytes)
    {
        return $this->value;
    }
}

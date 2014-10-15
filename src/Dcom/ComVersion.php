<?php

namespace Aztech\Dcom;

class ComVersion
{
    private $major = 5;

    private $minor = 0;

    public function __construct($major, $minor)
    {
        $this->major = (int) $major;
        $this->minor = (int) $minor;
    }

    public function getMajor()
    {
        return $this->major;
    }

    public function getMinor()
    {
        return $this->minor;
    }
}

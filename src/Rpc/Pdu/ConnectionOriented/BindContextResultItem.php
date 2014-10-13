<?php

namespace Aztech\Rpc\Pdu\ConnectionOriented;

use Rhumsaa\Uuid\Uuid;

class BindContextResultItem
{

    private $abstractSyntax;

    private $version;

    private $result;

    private $reason;

    public function __construct($result, $reason, Uuid $abstractSyntax = null, $version = 0)
    {
        $this->abstractSyntax = $abstractSyntax;
        $this->version = $version;
        $this->result = $result;
        $this->reason = $reason;
    }

    public function getSyntax()
    {
        return $this->abstractSyntax;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getReason()
    {
        return $this->reason;
    }
}

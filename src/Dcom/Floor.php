<?php

namespace Aztech\Dcom;

class Floor
{
    private $lhs;

    private $rhs;

    public function __construct($proto, $lhsLen, $rhsLen, $lhs, $rhs)
    {
        $this->proto = $proto;

        $this->lhsLen = $lhsLen;
        $this->rhsLen = $rhsLen;
        $this->lhs = $lhs;
        $this->rhs = $rhs;
    }

    public function getProtocolId()
    {
        return $this->proto;
    }

    public function getLhs()
    {
        return $this->lhs;
    }

    public function getRhs()
    {
        return $this->rhs;
    }
}

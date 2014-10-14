<?php

namespace Aztech\Dcom\Marshalling;

class SecurityBinding
{
    private $authnsvc;

    private $authzsvc;

    private $principalName;

    public function __construct($authnsvc, $authzsvc, $principalName)
    {
        $this->authnsvc = $authnsvc;
        $this->authzsvc = $authzsvc;
        $this->principalName = $principalName;
    }

    public function getAuthnSvc()
    {
        return $this->authnsvc;
    }

    public function getAuthzSvc()
    {
        return $this->authzsvc;
    }

    public function getPrincipalName()
    {
        return $this->principalName;
    }
}

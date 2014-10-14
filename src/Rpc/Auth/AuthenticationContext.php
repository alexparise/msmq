<?php

namespace Aztech\Rpc\Auth;

class AuthenticationContext
{

    const AUTH_NONE = 0x00;

    const AUTH_NTLM = 0x0a;

    private $contextId;

    private $authLevel;

    private $authType;

    public function __construct($contextId, $authLevel, $authType)
    {
        $this->contextId = $contextId;
        $this->authLevel = $authLevel;
        $this->authType = $authType;
    }

    public function getContextId()
    {
        return $this->contextId;
    }

    public function getAuthLevel()
    {
        return $this->authLevel;
    }

    public function getAuthType()
    {
        return $this->authType;
    }
}

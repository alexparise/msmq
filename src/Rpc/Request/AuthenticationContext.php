<?php

namespace Aztech\Rpc;

class AuthenticationContext
{
    
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
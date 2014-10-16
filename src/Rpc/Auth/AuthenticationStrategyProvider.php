<?php

namespace Aztech\Rpc\Auth;

interface AuthenticationStrategyProvider
{
    /**
     * @return AuthenticationStrategy
     */
    public function getStrategy();
}

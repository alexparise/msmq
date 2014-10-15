<?php

namespace Aztech\Rpc;

interface AuthenticationStrategyProvider
{
    /**
     * @return AuthenticationStrategy
     */
    public function getStrategy();
}

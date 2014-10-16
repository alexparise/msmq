<?php

namespace Aztech\Ntlm\Session;

use Aztech\Ntlm\ClientSession;
use Aztech\Ntlm\ServerChallenge;

class Ntlm2Session implements ClientSession
{

    public function __construct($machine, $user, $userDomain, $password, $randomSessionKey)
    {
        throw new \BadMethodCallException();
    }

    public function getLocalMachine()
    {
        throw new \BadMethodCallException();
    }

    public function getMachine()
    {
        throw new \BadMethodCallException();
    }

    public function getSessionKey()
    {
        throw new \BadMethodCallException();
    }

    public function getUser()
    {
        throw new \BadMethodCallException();
    }

    public function getUserDomain()
    {
        throw new \BadMethodCallException();
    }

    public function fork($randomSessionKey)
    {
        throw new \BadMethodCallException();
    }

    public function generateChallengeResponse(ServerChallenge $challenge)
    {
        throw new \BadMethodCallException();
    }
}

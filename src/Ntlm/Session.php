<?php

namespace Aztech\Ntlm;

class Session
{

    private $key;

    private $machine;

    private $user;

    private $userDomain;

    public function __construct($machine, $user, $userDomain, $key = null)
    {
        $this->key = $key;
        $this->machine = $machine;
        $this->user = $user;
        $this->userDomain = $userDomain;
    }

    public function hasKey()
    {
        return ($this->key == null);
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function getLocalMachine()
    {
        return gethostname();
    }

    public function getMachine()
    {
        return $this->machine;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getUserDomain()
    {
        return $this->userDomain;
    }
}

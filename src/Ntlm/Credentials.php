<?php

namespace Aztech\Ntlm;

class Credentials
{

    private $domain;

    private $password;

    private $user;

    private $userDomain;

    private $workstation;

    public function __construct($user, $password, $domain, $workstation)
    {
        $this->user = $user;
        $this->password = $password;
        $this->domain = trim($domain);
        $this->userDomain = trim($domain);
        $this->workstation = trim($workstation);
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getUserDomain()
    {
        return $this->userDomain;
    }

    public function getWorkstation()
    {
        return $this->workstation;
    }

    public function discardPassword()
    {
        unset($this->password);
    }
}

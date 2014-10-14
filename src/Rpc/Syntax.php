<?php

namespace Aztech\Rpc;

use Rhumsaa\Uuid\Uuid;

class Syntax
{
    private $uuid;

    private $version;

    public function __construct($uuid, $version)
    {
        if (! ($uuid instanceof Uuid)) {
            $uuid = Uuid::fromBytes(hex2bin($uuid));
        }

        $this->uuid = $uuid;
        $this->version = $version;
    }

    /**
     *
     * @return \Rhumsaa\Uuid\Uuid
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    public function getVersion()
    {
        return $this->version;
    }
}

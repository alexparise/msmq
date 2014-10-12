<?php

namespace Aztech\Rpc\Request;

use Aztech\Rpc\Request;
use Aztech\Rpc\Rpc;
use Aztech\Net\PacketWriter;
use Aztech\Net\ByteOrder;
use Rhumsaa\Uuid\Uuid;

class CoRequest implements Request
{

    private $body;

    private $contextId;

    private $opNum;

    private $iid;

    private $uuid;

    public function __construct($body, $contextId, $opNum, Uuid $iid, Uuid $uuid = null)
    {
        $this->body = $body;
        $this->contextId = $contextId;
        $this->opNum = $opNum;
        $this->iid = $iid;
        $this->uuid = $uuid;
    }

    public function getType()
    {
        return Rpc::REQUEST;
    }

    public function getContent()
    {
        $requestMessage = new PacketWriter();

        // RPC
        $requestMessage->writeUInt32(strlen($this->body), ByteOrder::LITTLE_ENDIAN);
        $requestMessage->writeUInt16($this->contextId, ByteOrder::LITTLE_ENDIAN);
        $requestMessage->writeUInt16($this->opNum, ByteOrder::LITTLE_ENDIAN);

        if ($this->iid) {
            $requestMessage->write($this->iid->getBytes());
        }

        if ($this->uuid) {
            $requestMessage->write($this->uuid->getBytes());
        }

        // RPCBody : ORPCThis
        $requestMessage->write($this->body);


        return $requestMessage->getBuffer();
    }

    public function getFlags()
    {
        return $this->uuid ? Rpc::PFC_OBJECT_UUID : 0;
    }
}

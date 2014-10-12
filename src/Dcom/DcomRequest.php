<?php

namespace Aztech\Dcom;

use Rhumsaa\Uuid\Uuid;
use Aztech\Net\PacketWriter;

class DcomRequest
{

    private $clsId;

    private $iid;

    private $orpc;

    public function __construct(Uuid $clsId, array $iid)
    {
        $this->iid = $iid;
        $this->clsId = $clsId;
        $this->orpc = new OrpcThis(Uuid::uuid4());
    }

    public function getContent()
    {
        $buffer = new PacketWriter();

        // RPCBody : ORPCThis
        $buffer->write($this->orpc->getContent());
        // DCOM
        // ClsId
        $buffer->write($this->clsId->getBytes());
        // OBJREF Count ???
        $buffer->writeUInt32(0);
        // ??
        $buffer->writeUInt32(0);
        // Client imp level
        $buffer->writeUInt32(0);
        // Mode
        $buffer->writeUInt32(0);
        // IID count
        $buffer->writeUInt32(count($this->iid));
        // ???
        $buffer->write(pack('H*', '803F140001000000'));

        foreach ($this->iid as $iid) {
            // IID
            $buffer->write($iid->getBytes());
        }

        // RequestedProtSeq
        $buffer->writeUInt32(1);
        $buffer->writeUInt32(1);
        // Type (tcp)
        $buffer->writeUInt16(7);


        return $buffer->getBuffer();
    }
}

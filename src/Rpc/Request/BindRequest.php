<?php

namespace Aztech\Rpc\Request;

use Aztech\Rpc\Request;
use Aztech\Net\PacketWriter;
use Aztech\Rpc\Rpc;
use Aztech\Net\ByteOrder;
use Aztech\Rpc\BindContext;

class BindRequest implements Request
{

    private $context;

    public function __construct(BindContext $context)
    {
        $this->context = $context;
    }

    public function getFlags()
    {
        return 0;
    }

    public function getContent()
    {
        $bindPacket = new PacketWriter();

        $bindPacket->writeUInt16(Rpc::FRAG_SZ, ByteOrder::LITTLE_ENDIAN);
        $bindPacket->writeUInt16(Rpc::FRAG_SZ, ByteOrder::LITTLE_ENDIAN);
        $bindPacket->writeUInt32(0, ByteOrder::LITTLE_ENDIAN);
        $bindPacket->writeUInt32($this->context->getItemCount());

        foreach ($this->context->getItems() as $item) {
            $bindPacket->writeUInt16($item->getContextId(), ByteOrder::LITTLE_ENDIAN);
            $bindPacket->writeUInt16($item->getTransferSyntaxCount());

            $bindPacket->write($item->getAbstractSyntax()->getBytes());
            $bindPacket->writeUInt32($item->getVersion(), ByteOrder::LITTLE_ENDIAN);

            foreach ($item->getTransferSyntaxes() as $transferSyntax) {
                $bindPacket->write($transferSyntax[0]->getBytes());
                $bindPacket->writeUInt32($transferSyntax[1], ByteOrder::LITTLE_ENDIAN);
            }
        }

        return $bindPacket->getBuffer();
    }

    public function getType()
    {
        return Rpc::BIND;
    }
}

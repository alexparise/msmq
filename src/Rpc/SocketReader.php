<?php

namespace Aztech\Rpc;

use Aztech\Net\SocketReader as BaseSocketReader;
use Aztech\Util\Text;
use Aztech\Net\PacketReader;
use Aztech\Net\ByteOrder;

class SocketReader extends BaseSocketReader
{

    public function readResponse()
    {
        $body = '';

        do {
            $packet = '';
            $header = null;

            if ($header == null) {
                $header  = ConnectionOrientedHeader::parse($this);
                $packet .= $header->getBytes();
            }

            while ($this->getReadByteCount() != $header->getPacketSize()) {
                $packet .= $this->read($header->getPacketSize() - $this->getReadByteCount());
            }

            if ($header->getType() == Rpc::RESPONSE) {
                $offset = Rpc::CO_HDR_SZ + Rpc::CO_RESP_HDR_SZ;

                if ($header->getAuthSize() > 0) {
                    $offset += ($header->getAuthSize() + Rpc::AUTH_HDR_SZ);
                }

                $body .= substr($packet, $offset);
            }
            elseif ($header->getType() == Rpc::BIND_ACK
                || $header->getType() == Rpc::BIND_NACK
                || $header->getType() == Rpc::SHUTDOWN
            ) {
                $body .= $packet;
            }
            elseif ($header->getType() == Rpc::ALTER_CONTEXT_RESP) {
                $body .= $packet;
            }
            elseif ($header->getType() == Rpc::FAULT) {
                $reader = new PacketReader($packet);
                $reader->skip(28);
                $code = $reader->readUInt32(ByteOrder::LITTLE_ENDIAN);

                throw new RpcException('DCE/RPC fault 0x' . str_pad(dechex($code), 8, '0', STR_PAD_LEFT), $code);
            }
            else {
                throw new \RuntimeException('Unknown packet type.');
            }
        }
        while (! $header->isLastFragment());

        return new Response($header, $body);
    }
}

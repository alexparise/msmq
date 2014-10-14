<?php

namespace Aztech\Dcom\Common;

use Aztech\Dcom\DcomInterface;
use Rhumsaa\Uuid\Uuid;
use Aztech\Dcom\Marshalling\MarshalledBuffer;
use Aztech\Dcom\Marshalling\UnmarshallingBuffer;
use Aztech\Net\Buffer\BufferReader;
use Aztech\Net\DataTypes;
use Aztech\Dcom\Marshalling\StringBinding;
use Aztech\Dcom\Marshalling\SecurityBinding;
use Aztech\Dcom\Marshalling\DualStringArray;
use Aztech\Util\Text;

class IOxIdResolver extends CommonInterface
{
    const IID = 'C4FEFC9960521B10BBCB00AA0021347A';

    private $ops = [
       'ResolveOxId'   => 0x00,
       'SimplePing'    => 0x01,
       'ComplexPing'   => 0x02,
       'ServerAlive'   => 0x03,
       'ResolveOxId2'  => 0x04,
       'ServerAlive2'  => 0x05
    ];

    protected $noAuth = true;

    protected function getIid()
    {
        return Uuid::fromBytes(hex2bin(self::IID));
    }

    public function ResolveOxId(Uuid $oxid, array $protocolSequences)
    {
        $op = $this->ops[__FUNCTION__];

        $in = new MarshalledBuffer();
        $out = new UnmarshallingBuffer();

        $buffer = $in->getWriter();

        // DCOM
        // ClsId
        $buffer->write($oxid->getBytes());
        // OBJREF Count ???
        $buffer->writeUInt32(0);
        // ??
        $buffer->writeUInt32(0);
        // Client imp level
        $buffer->writeUInt32(0);
        // Mode
        $buffer->writeUInt32(0);
        // IID count
        $buffer->writeUInt32(1);
        // ???
        $buffer->write(pack('H*', '803F140001000000'));

        $buffer->write($this->getIid()->getBytes());

        // RequestedProtSeq
        $buffer->writeUInt32(1);
        $buffer->writeUInt32(1);
        // Type (tcp)
        $buffer->writeUInt16(7);

        return $this->execute($this->client, 0, $in, $out);
    }

    public function ServerAlive()
    {
        return $this->execute($this->client, $this->ops[__FUNCTION__]);
    }

    public function ServerAlive2()
    {
        $response = $this->execute($this->client, $this->ops[__FUNCTION__]);
        $reader = new BufferReader($response->getBody());

        $major = $reader->readUInt16();
        $minor = $reader->readUInt16();
        $unknown = $reader->read(8);

        $numEntries = $reader->readUInt16();
        $securityOffset = $reader->readUInt16();

        $offset = $reader->getReadByteCount();

        $stringBindings = [];
        $securityBindings = [];

        while (ceil(($reader->getReadByteCount() - $offset + 1) / DataTypes::INT16_SZ) < $securityOffset) {
            $towerId = $reader->readUInt16();
            $networkAddress = Text::fromUnicode($reader->readTo("0000", true));

            $stringBindings[] = new StringBinding($networkAddress, $towerId);
        }

        $reader->read(2);

        while (ceil(($reader->getReadByteCount() - $offset + 1) / DataTypes::INT16_SZ) < $numEntries) {
            $authnSvc = $reader->readUInt16();
            $authzSvc = $reader->readUInt16();
            $principalName = $reader->readTo("0000");

            $securityBindings[] = new SecurityBinding($authnSvc, $authzSvc, $principalName);
        }

        return new DualStringArray($stringBindings, $securityBindings);
    }
}

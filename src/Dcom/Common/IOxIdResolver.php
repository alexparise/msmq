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
use Aztech\Util\Guid;
use Aztech\Dcom\Marshalling\Marshaller\ComVersionMarshaller;
use Aztech\Dcom\Marshalling\Marshaller\DualStringArrayMarshaller;
use Aztech\Dcom\Marshalling\Marshaller\PrimitiveMarshaller;
use Aztech\Dcom\Marshalling\Marshaller\GuidMarshaller;

class IOxIdResolver extends CommonInterface
{
    const IID = '{99fcfec4-5260-101b-bbcb-00aa0021347a}';

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
        return Guid::fromString(self::IID);
    }

    public function ResolveOxId($oxid, array $protocolSequences = [])
    {
        //$this->noAuth = false;

        $op = $this->ops[__FUNCTION__];

        $in = new MarshalledBuffer();
        $out = new UnmarshallingBuffer();

        $in->add(PrimitiveMarshaller::UInt64(), $oxid);
        $in->add(PrimitiveMarshaller::UInt16(), count($protocolSequences));
        $in->add(PrimitiveMarshaller::UInt16(), $protocolSequences);

        $out->add(new DualStringArrayMarshaller());
        $out->add(new GuidMarshaller());
        $out->add(PrimitiveMarshaller::UInt32());

        return $this->execute($this->client, 0, $in, $out);
    }

    public function ServerAlive()
    {
        return $this->execute($this->client, $this->ops[__FUNCTION__]);
    }

    public function ServerAlive2(& $bindings)
    {
        $out = new UnmarshallingBuffer();

        $out->add(new ComVersionMarshaller());
        $out->add(new DualStringArrayMarshaller(), 8);

        $response = $this->execute($this->client, $this->ops[__FUNCTION__], null, $out);
        $bindings = $out->getValues()[1];
    }
}

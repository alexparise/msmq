<?php

namespace Aztech\Dcom\Common;

use Aztech\Dcom\Marshalling\MarshalledBuffer;
use Aztech\Dcom\Marshalling\UnmarshallingBuffer;
use Aztech\Dcom\Marshalling\Marshaller\ComVersionMarshaller;
use Aztech\Dcom\Marshalling\Marshaller\DualStringArrayMarshaller;
use Aztech\Dcom\Marshalling\Marshaller\GuidMarshaller;
use Aztech\Dcom\Marshalling\Marshaller\PrimitiveMarshaller;
use Aztech\Util\Guid;
use Aztech\Util\Text;
use Rhumsaa\Uuid\Uuid;

class IOxIdResolver extends CommonInterface
{
    const IID = '{99fcfec4-5260-101b-bbcb-00aa0021347a}';

    private $ops = [
       'resolveOxId'   => 0x00,
       'simplePing'    => 0x01,
       'complexPing'   => 0x02,
       'serverAlive'   => 0x03,
       'resolveOxId2'  => 0x04,
       'serverAlive2'  => 0x05
    ];

    protected $noAuth = true;

    protected function getIid()
    {
        return Guid::fromString(self::IID);
    }

    public function resolveOxId($oxid, array $protocolSequences = [])
    {
        //$this->noAuth = false;

        $opnum = $this->ops[__FUNCTION__];

        $inBuffer = new MarshalledBuffer();
        $outBuffer = new UnmarshallingBuffer();

        $inBuffer->add(PrimitiveMarshaller::UInt64(), $oxid);
        $inBuffer->add(PrimitiveMarshaller::UInt16(), count($protocolSequences));
        $inBuffer->add(PrimitiveMarshaller::UInt16(), $protocolSequences);

        $outBuffer->add(new DualStringArrayMarshaller());
        $outBuffer->add(new GuidMarshaller());
        $outBuffer->add(PrimitiveMarshaller::UInt32());

        return $this->execute($this->client, $opnum, $inBuffer, $outBuffer);
    }

    public function serverAlive()
    {
        return $this->execute($this->client, $this->ops[__FUNCTION__]);
    }

    public function serverAlive2(& $bindings)
    {
        $outBuffer = new UnmarshallingBuffer();

        $outBuffer->add(new ComVersionMarshaller());
        $outBuffer->add(new DualStringArrayMarshaller(), 8);

        $this->execute($this->client, $this->ops[__FUNCTION__], null, $outBuffer);

        $bindings = $outBuffer->getValues()[1];
    }
}

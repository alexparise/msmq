<?php

namespace Aztech\Dcom\Common;

use Aztech\Dcom\DcomInterface;
use Rhumsaa\Uuid\Uuid;
use Aztech\Dcom\Marshalling\MarshalledBuffer;
use Aztech\Dcom\Marshalling\UnmarshallingBuffer;
use Aztech\Util\Guid;
use Aztech\Dcom\Marshalling\Marshaller\OrpcThisMarshaller;
use Aztech\Dcom\Marshalling\Marshaller\GuidMarshaller;
use Aztech\Dcom\Marshalling\Marshaller\PrimitiveMarshaller;
use Aztech\Net\DataTypes;
use Aztech\Dcom\Marshalling\Marshaller\SizedByteArrayMarshaller;

class IRemoteActivation extends CommonInterface
{

    const IID = '{4d9f4ab8-7d1c-11cf-861e-0020af6e7c57}';

    const IUNK = '00000000-0000-0000-C000-000000000046';

    protected function getIid()
    {
        return Guid::fromString(self::IID);
    }

    public function remoteActivation(Uuid $clsid, array $iids, array $protocolSequences)
    {
        $in = new MarshalledBuffer();
        $out = new UnmarshallingBuffer();

        $in->add(new OrpcThisMarshaller(), $this->getOrpcThis());
        $in->add(new GuidMarshaller(), $clsid);
        $in->add(new PrimitiveMarshaller(DataTypes::BYTES), "name");
        $in->add(new SizedByteArrayMarshaller(), []);
        $in->add(PrimitiveMarshaller::UInt32(), 0);
        $in->add(PrimitiveMarshaller::UInt32(), 0);
        $in->add(PrimitiveMarshaller::UInt32(), count($iids));
        $in->add(new PrimitiveMarshaller(DataTypes::BYTES), pack('H*', '803F140001000000'));
        $in->add(new GuidMarshaller(), $iids);
        $in->add(PrimitiveMarshaller::UInt16(), count($protocolSequences));
        $in->add(PrimitiveMarshaller::UInt16(), $protocolSequences);

        $response = $this->execute($this->client, 0x00, $in, $out);
    }
}

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
use Aztech\Dcom\Marshalling\Marshaller\SizedStringMarshaller;
use Aztech\Dcom\Marshalling\Marshaller\UnicodeStringMarshaller;
use Aztech\Dcom\ObjRef;
use Aztech\Dcom\Marshalling\Marshaller\PointerMarshaller;
use Aztech\Dcom\Pointer;
use Aztech\Dcom\Marshalling\Marshaller\SizedArrayMarshaller;

class IRemoteActivation extends CommonInterface
{

    protected $noAuth = true;

    const IID = '{4d9f4ab8-7d1c-11cf-861e-0020af6e7c57}';

    const IUNK = '00000000-0000-0000-C000-000000000046';

    protected function getIid()
    {
        return Guid::fromString(self::IID);
    }

    public function remoteActivation(Uuid $clsid, array $iids, array $protocolSequences)
    {
        $inBuffer = new MarshalledBuffer();
        $outBuffer = new UnmarshallingBuffer();

        $inBuffer->add(new OrpcThisMarshaller(), $this->getOrpcThis());
        $inBuffer->align();
        $inBuffer->add(new GuidMarshaller(), $clsid);
        $inBuffer->align();
        $inBuffer->add(new PointerMarshaller(new UnicodeStringMarshaller()), new Pointer("NAME"));
        $inBuffer->align();
        // Object storage
        //    MInterface Ptr
        $inBuffer->add(PrimitiveMarshaller::UInt32(), 2);
        $inBuffer->add(PrimitiveMarshaller::UInt32(), 1);
        $inBuffer->add(PrimitiveMarshaller::UInt32(), 16);
        $inBuffer->add(PrimitiveMarshaller::UInt32(), 1);
        $inBuffer->add(PrimitiveMarshaller::UInt32(), ObjRef::OBJREF_CUSTOM);

        //$inBuffer->add(new PointerMarshaller(new SizedArrayMarshaller(new GuidMarshaller())), new Pointer($iids));
        // OBJREF

        $inBuffer->add(PrimitiveMarshaller::UInt32(), 2); // Client impl level
        $inBuffer->add(PrimitiveMarshaller::UInt32(), 0); // Mode
        $inBuffer->add(PrimitiveMarshaller::UInt32(), count($iids)); // Mode

        foreach($iids as $iid) {
            $inBuffer->add(new PointerMarshaller(new SizedArrayMarshaller(new GuidMarshaller())), new Pointer([ $iid ]));
        }



        $this->execute($this->client, 0x00, $inBuffer, $outBuffer);
    }
}

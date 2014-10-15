<?php

namespace Aztech\Dcom\Common;

use Aztech\Dcom\DcomInterface;
use Aztech\Util\Guid;
use Aztech\Rpc\TransferSyntax;
use Aztech\Dcom\Marshalling\MarshalledBuffer;
use Aztech\Dcom\Marshalling\Marshaller\PrimitiveMarshaller;
use Rhumsaa\Uuid\Uuid;
use Aztech\Dcom\Marshalling\Marshaller\GuidMarshaller;
use Aztech\Rpc\Client;
use Aztech\Dcom\Marshalling\UnmarshallingBuffer;
use Aztech\Dcom\Marshalling\Marshaller\ComVersionMarshaller;
use Aztech\Dcom\Marshalling\Marshaller\HandleMarshaller;
use Aztech\Dcom\Handle;
use Aztech\Net\Buffer\BufferReader;
use Aztech\Util\Text;
use Aztech\Dcom\Marshalling\Marshaller\TowerMarshaller;
use Aztech\Dcom\Marshalling\Marshaller\EndpointEntryMarshaller;

class EndPointMapper extends DcomInterface
{
    const IID = '{e1af8308-5d1f-11c9-91a4-08002b14a0fa}';

    private $client;

    public function __construct(Client $client)
    {
        parent::__construct(Guid::fromString(self::IID), 0x03, [ TransferSyntax::getNdr() ]);


        $this->noAuth = true;
        $this->client = $client;
    }

    public function ept_lookup($type = 0x00, Uuid $service = null, UUid $interface = null, $maxEntries = 500, & $handle = null, & $entries = null, & $result = null)
    {
        $in = new MarshalledBuffer();
        $out = new UnmarshallingBuffer();

        $in->add(PrimitiveMarshaller::UInt32(), $type);
        $in->add(PrimitiveMarshaller::UInt32(), 1);
        $in->add(new GuidMarshaller(), $service ?: Guid::null());
        $in->add(PrimitiveMarshaller::UInt32(), 2);
        $in->add(new GuidMarshaller(), $interface ?: Guid::null());
        $in->add(PrimitiveMarshaller::UInt16(), 2);
        $in->add(PrimitiveMarshaller::UInt16(), 0);
        $in->add(PrimitiveMarshaller::UInt32(), 0);
        $in->add(new HandleMarshaller(), Handle::null());
        $in->add(PrimitiveMarshaller::UInt32(), min(500, (max(0, $maxEntries))));

        $out->add(new HandleMarshaller());
        $out->add(new EndpointEntryMarshaller());
        $out->add(PrimitiveMarshaller::UInt32());

        $response = $this->execute($this->client, 0x02, $in, $out);

        list($handle, $entries, $result) = $out->getValues();
    }
}

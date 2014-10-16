<?php

namespace Aztech\Dcom\Common;

use Aztech\Dcom\DcomInterface;
use Aztech\Dcom\Handle;
use Aztech\Dcom\Marshalling\MarshalledBuffer;
use Aztech\Dcom\Marshalling\UnmarshallingBuffer;
use Aztech\Dcom\Marshalling\Marshaller\ComVersionMarshaller;
use Aztech\Dcom\Marshalling\Marshaller\EndpointEntryMarshaller;
use Aztech\Dcom\Marshalling\Marshaller\GuidMarshaller;
use Aztech\Dcom\Marshalling\Marshaller\HandleMarshaller;
use Aztech\Dcom\Marshalling\Marshaller\PrimitiveMarshaller;
use Aztech\Dcom\Marshalling\Marshaller\TowerMarshaller;
use Aztech\Net\Buffer\BufferReader;
use Aztech\Rpc\Client;
use Aztech\Rpc\TransferSyntax;
use Aztech\Util\Guid;
use Aztech\Util\Text;
use Rhumsaa\Uuid\Uuid;

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

    public function eptLookup($type = 0x00, Uuid $service = null, UUid $interface = null, $maxEntries = 500, & $handle = null, & $entries = null, & $result = null)
    {
        $inBuffer = new MarshalledBuffer();
        $outBuffer = new UnmarshallingBuffer();

        $inBuffer->add(PrimitiveMarshaller::UInt32(), $type);
        $inBuffer->add(PrimitiveMarshaller::UInt32(), 1);
        $inBuffer->add(new GuidMarshaller(), $service ?: Guid::null());
        $inBuffer->add(PrimitiveMarshaller::UInt32(), 2);
        $inBuffer->add(new GuidMarshaller(), $interface ?: Guid::null());
        $inBuffer->add(PrimitiveMarshaller::UInt16(), 2);
        $inBuffer->add(PrimitiveMarshaller::UInt16(), 0);
        $inBuffer->add(PrimitiveMarshaller::UInt32(), 0);
        $inBuffer->add(new HandleMarshaller(), Handle::null());
        $inBuffer->add(PrimitiveMarshaller::UInt32(), min(500, (max(0, $maxEntries))));

        $outBuffer->add(new HandleMarshaller());
        $outBuffer->add(new EndpointEntryMarshaller());
        $outBuffer->add(PrimitiveMarshaller::UInt32());

        $this->execute($this->client, 0x02, $inBuffer, $outBuffer);

        list($handle, $entries, $result) = $outBuffer->getValues();
    }
}

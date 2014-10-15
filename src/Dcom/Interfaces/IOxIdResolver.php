<?php

namespace Aztech\Dcom\Interfaces;

/**
 * @guid {99fcfec4-5260-101b-bbcb-00aa0021347a}
 * @ns "\Aztech\Dcom\Marshalling\Marshaller"
 * @author thibaud
 *
 */
interface IOxIdResolver
{
    /**
     * @opnum 0x00
     * @in UInt64
     * @in ProtocolRequest
     * @out DualStringArray
     * @out Guid
     * @out UInt32
     * @result UInt32
     */
    public function resolveOxId($oxid, ProtocolRequest $requestedProtSeqs = null);

    /**
     * @opnum 0x01
     * @in UInt64
     * @result UInt32
     */
    public function simplePing($pingSetId);

    /**
     * @opnum 0x02
     * @in:out UInt64
     * @in:ref count(cAddToSet)
     * @in:ref count(cDelFromSet)
     * @in(cAddToSet) GuidMarshaller[]
     * @in(cDelFromSet) GuidMarshaller[]
     * @result UInt32
     */
    public function complexPing(& $pingSetId = 0, array $addOids = [], array $delOids = []);
}

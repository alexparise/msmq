<?php

namespace Aztech\Dcom\Marshalling;

use Aztech\Net\Reader;
use Aztech\Net\Writer;

interface Marshaller
{

    /**
     *
     * @param mixed $value
     */
    public function marshall(Writer $writer, $value);

    /**
     *
     * @param Reader $reader
     */
    public function unmarshallNext(Reader $reader);
}

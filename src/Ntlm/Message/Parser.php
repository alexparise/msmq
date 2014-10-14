<?php

namespace Aztech\Ntlm\Message;

use Aztech\Ntlm\NTLMSSP;
use Aztech\Net\Reader;
use Aztech\Util\Text;
use Aztech\Net\ByteOrder;
use Aztech\Ntlm\ServerChallenge;

class Parser
{
    public function parse($packet)
    {
        $packet = new NtlmPacketReader($packet);

        $signature = $packet->readSignature();

        if ($signature !== NTLMSSP::SIGNATURE . chr(0)) {
            echo PHP_EOL . "\033[31;1mInvalid NTLM packet\033[0m :" . PHP_EOL;
            Text::dumpHex($packet->getBuffer());
            echo PHP_EOL;

            throw new \RuntimeException('Packet signature is not valid. [' . reset(unpack('H*', $signature)) . ']');
        }

        $type = $packet->readUInt32(ByteOrder::LITTLE_ENDIAN);

        switch ($type) {
            case NTLMSSP::MSG_CHALLENGE:
                return $this->parseChallenge($packet);
            default:
                throw new \BadMethodCallException('Server features not implemented.');
        }
    }

    private function parseChallenge(NtlmPacketReader $packet)
    {
        $target = $packet->readString();
        $flags = $packet->readUInt32();
        $nonce = $packet->read(8);

        $packet->read(16);

        $contextLower = $packet->readUInt32();
        $contextUpper = $packet->readUInt32();

        $challenge = new ServerChallenge($nonce, $flags, $target);

        return new ChallengeMessage($challenge, $contextLower, $contextUpper);
    }
}

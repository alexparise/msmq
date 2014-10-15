<?php

namespace Aztech\Util;

class Text
{
    public static function toUnicode($text)
    {
        return iconv('UTF-8', 'UTF-16LE', $text);
    }

    public static function fromUnicode($unicodeText)
    {
        return iconv('UTF-16LE', 'UTF-8', $unicodeText);
    }

    public static function dumpHex($bytes, $caption = '')
    {
        if ($caption) {
            echo PHP_EOL . 'HEXDUMP -- ' . $caption . PHP_EOL;
        }

        $unpacked = unpack('H*', $bytes);
        $hexDump = reset($unpacked);

        $inc = 32;

        for ($i = 0; $i < strlen($hexDump); $i += $inc) {
            $buffer  = str_pad($i / 2, 4, '0', STR_PAD_LEFT) . '-';
            $buffer .= str_pad((($i + $inc) / 2) - 1, 4, '0', STR_PAD_LEFT);
            $buffer .= ' [ ';

            for ($j = $i; $j < $i + $inc && $j < strlen($hexDump); $j += 2) {
                $buffer .= substr($hexDump, $j, 2);

                if ($j + 2 == $i + ($inc / 2)) {
                    $buffer .= ' ';
                }

                $buffer .= ' ';
            }

            $buffer = str_pad($buffer, 61, ' ');
            $buffer .= '] ';

            echo $buffer;

            $packedHex = pack('H*', substr($hexDump, $i, $inc));

            for ($j = 0; $j < strlen($packedHex); $j++) {
                $ord = ord($packedHex[$j]);
                if ($ord < 32 || $ord > 126) {
                    echo '.';
                }
                else {
                    echo $packedHex[$j];
                }
            }

            echo PHP_EOL;
        }
    }
}

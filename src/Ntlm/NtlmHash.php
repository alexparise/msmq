<?php

namespace Aztech\Ntlm;

use Aztech\Util\Hash;
use Aztech\Util\Text;

final class NtlmHash
{
    public static function des($k, $l)
    {
        if (strlen($k) !== 7) {
            throw new \InvalidArgumentException('Key must be 7 bytes.');
        }

        return Hash::encryptDes($k, $l);
    }

    public static function desl($k, $d)
    {
        if (strlen($k) !== 16) {
            throw new \InvalidArgumentException('Key must be 16 bytes.');
        }

        $cipher  = Hash::encryptDes(Hash::convertKey(substr($k, 0, 7)), $d);
        $cipher .= Hash::encryptDes(Hash::convertKey(substr($k, 7, 7)), $d);
        $cipher .= Hash::encryptDes(Hash::convertKey(str_pad(substr($k, 14), 7, "\0")), $d);

        return $cipher;
    }

    public static function md4($text)
    {
        return Hash::hashMd4($text);
    }

    public static function ntowfv1($password)
    {
        return Hash::hashMd4(Text::toUnicode($password));
    }

    public static function lmowfv1($password)
    {
        return Hash::hashLm($password);
    }

    public static function rc4($k, $d)
    {
        return Hash::encryptRc4($k, $d);
    }

    public static function rand($bytes)
    {
        return openssl_random_pseudo_bytes($bytes);
    }

}

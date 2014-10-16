<?php

namespace Aztech\Ntlm;

use Aztech\Util\Hash;
use Aztech\Util\Text;

final class NtlmHash
{
    public static function des($key, $data)
    {
        if (strlen($key) !== 7) {
            throw new \InvalidArgumentException('Key must be 7 bytes.');
        }

        return Hash::encryptDes($key, $data);
    }

    public static function desl($key, $data)
    {
        if (strlen($key) !== 16) {
            throw new \InvalidArgumentException('Key must be 16 bytes.');
        }

        $cipher  = Hash::encryptDes(Hash::convertKey(substr($key, 0, 7)), $data);
        $cipher .= Hash::encryptDes(Hash::convertKey(substr($key, 7, 7)), $data);
        $cipher .= Hash::encryptDes(Hash::convertKey(str_pad(substr($key, 14), 7, "\0")), $data);

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

    public static function rc4($key, $data)
    {
        return Hash::encryptRc4($key, $data);
    }

    public static function rand($bytes)
    {
        return openssl_random_pseudo_bytes($bytes);
    }

}

<?php

namespace Aztech\Util;

/**
 * Shameless port of http://cpansearch.perl.org/src/UMVUE/Authen-Perl-NTLM-0.12/lib/Authen/Perl/NTLM.pm
 * @author thibaud
 *
 */
class Hash
{

    const LM_MAGIC = '4B47532140232425';

    const LM_MAX_LEN = 14;

    const NTLM_RESP_KEY_LENGTH = 21;

    const NTLM_RESP_NONCE_LENGTH = 8;

    public static function hashLm($password)
    {
        // Magic string for LM hash
        $magic = pack('H16', self::LM_MAGIC);

        while (strlen($password) < self::LM_MAX_LEN) {
            $password .= chr(0);
        }

        // Keep max allowed chars (14)
        $password = substr($password, 0, self::LM_MAX_LEN);
        // Convert to uppercase
        $password = strtoupper($password);

        $key  = self::convertKey(substr($password, 0, self::LM_MAX_LEN / 2));
        $key .= self::convertKey(substr($password, self::LM_MAX_LEN / 2, self::LM_MAX_LEN));

        $cipherA = self::encryptDes(substr($key, 0, 8), hex2bin(self::LM_MAGIC));
        $cipherB = self::encryptDes(substr($key, 8, 8), hex2bin(self::LM_MAGIC));

        return $cipherA . $cipherB;
    }

    public static function hashNt($password)
    {
        $ntPassword = Text::toUnicode($password);

        return hash('md4', $ntPassword, true) . pack("H10", "0000000000");
    }

    public static function hashMd4($text)
    {
        return hash('md4', $text, true);
    }

    public static function calcNtlmResponse($key, $nonce)
    {
        if (strlen($nonce) != self::NTLM_RESP_NONCE_LENGTH) {
            echo PHP_EOL . "\033[31;1mInvalid nonce\033[0m :" . PHP_EOL;
            Text::dumpHex($nonce);
            echo PHP_EOL;

            throw new \InvalidArgumentException('Nonce must be 8 bytes. [ ' . strlen($nonce) . ' bytes]');
        }

        if (strlen($key) < self::NTLM_RESP_KEY_LENGTH) {
            $key = str_pad($key, self::NTLM_RESP_KEY_LENGTH, "\000");
        }

        $cipher  = self::encryptDes(self::convertKey(substr($key, 0, 7)), $nonce);
        $cipher .= self::encryptDes(self::convertKey(substr($key, 7, 7)), $nonce);
        $cipher .= self::encryptDes(self::convertKey(substr($key, 14, 7)), $nonce);

        return $cipher;
    }

    public static function encryptRc4($key, $str)
    {
        $s = array();

        for ($i = 0; $i < 256; $i++) {
            $s[$i] = $i;
        }

        $j = 0;

        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
        }

        $i = 0;
        $j = 0;
        $res = '';

        for ($y = 0; $y < strlen($str); $y++) {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
            $res .= $str[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
        }

        return $res;
    }

    public static function encryptDes($key, $data)
    {
        // http://php.net/manual/fr/ref.hash.php#84587
        $is = mcrypt_get_iv_size(MCRYPT_DES, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($is, MCRYPT_RAND);

        return mcrypt_encrypt(MCRYPT_DES, $key, $data, MCRYPT_MODE_ECB, $iv);
    }

    public static function convertKey($key)
    {
        if (strlen($key) !== self::LM_MAX_LEN / 2) {
            throw new \InvalidArgumentException('Key should be exactly 7 bytes.');
        }

        $byte = [];

        $byte[0] = substr($key, 0, 1);
        $byte[1] = chr(((ord(substr($key, 0, 1)) << 7) & 0xFF) | (ord(substr($key, 1, 1)) >> 1));
        $byte[2] = chr(((ord(substr($key, 1, 1)) << 6) & 0xFF) | (ord(substr($key, 2, 1)) >> 2));
        $byte[3] = chr(((ord(substr($key, 2, 1)) << 5) & 0xFF) | (ord(substr($key, 3, 1)) >> 3));
        $byte[4] = chr(((ord(substr($key, 3, 1)) << 4) & 0xFF) | (ord(substr($key, 4, 1)) >> 4));
        $byte[5] = chr(((ord(substr($key, 4, 1)) << 3) & 0xFF) | (ord(substr($key, 5, 1)) >> 5));
        $byte[6] = chr(((ord(substr($key, 5, 1)) << 2) & 0xFF) | (ord(substr($key, 6, 1)) >> 6));
        $byte[7] = chr((ord(substr($key, 6, 1)) << 1) & 0xFF);

        $result = '';

        for ($i = 0; $i < 8; ++$i) {
            $byte[$i] = self::setOddParity($byte[$i]);
            $result .= $byte[$i];
        }

        return $result;
    }

    private static function setOddParity($byte)
    {
        if (strlen($byte) != 1) {
            throw new \InvalidArgumentException('Multi-byte inputs not accepted.');
        }

        $parity = 0;
        $ordByte = ord($byte);

        for ($i = 0; $i < 8; ++$i) {
            if ($ordByte & 0x01) {
                ++$parity;
            }

            $ordByte >>= 1;
        }

        $ordByte = ord($byte);

        if ($parity % 2 == 0) {
            if ($ordByte & 0x01) {
                $ordByte &= 0xFE;
            }
            else {
                $ordByte |= 0x01;
            }
        }

        return chr($ordByte);
    }
}

<?php
declare(strict_types=1);

namespace Axpwx;

/**
 * Base91编解码
 */
class Base91
{
    /**
     * 码表
     */
    private const ENC_TABLE = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd',
        'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7',
        '8', '9', '!', '#', '$', '%', '&', '(', ')', '*', '+', ',', '.', '/', ':',
        ';', '<', '=', '>', '?', '@', '[', ']', '^', '_', '`', '{', '|', '}', '~', '"'
    ];

    /**
     * Decode a base91 string.
     *
     * @param string $input The base91 string to decode.
     *
     * @return string  The decoded byte sequence.
     */
    public static function decode(string $input) : string
    {
        $b91DeTab = array_flip(self::ENC_TABLE);
        $l        = strlen($input);
        $v        = -1;
        $b        = $n = 0;
        $o        = '';
        for ($i = 0; $i < $l; ++$i) {
            $c = $b91DeTab[$input[$i]];
            if (!isset($c)) continue;
            if ($v < 0) {
                $v = $c;
            } else {
                $v += $c * 91;
                $b |= $v << $n;
                $n += ($v & 8191) > 88 ? 13 : 14;
                do {
                    $o .= chr($b & 255);
                    $b >>= 8;
                    $n -= 8;
                } while ($n > 7);
                $v = -1;
            }
        }
        if ($v + 1) {
            $o .= chr(($b | $v << $n) & 255);
        }

        return $o;
    }

    /**
     * Encode a byte sequence to base91.
     *
     * @param string $input The byte sequence to encode.
     *
     * @return string  The base91 encoded string.
     */
    public static function encode(string $input) : string
    {
        $l = strlen($input);
        $b = $n = 0;
        $o = '';
        for ($i = 0; $i < $l; ++$i) {
            $b |= ord($input[$i]) << $n;
            $n += 8;
            if ($n > 13) {
                $v = $b & 8191;
                if ($v > 88) {
                    $b >>= 13;
                    $n -= 13;
                } else {
                    $v = $b & 16383;
                    $b >>= 14;
                    $n -= 14;
                }
                $o .= self::ENC_TABLE[$v % 91] . self::ENC_TABLE[$v / 91];
            }
        }
        if ($n) {
            $o .= self::ENC_TABLE[$b % 91];
            if ($n > 7 || $b > 90) {
                $o .= self::ENC_TABLE[$b / 91];
            }
        }

        return $o;
    }

    /**
     * 对16进制字符串进行编码
     *
     * @param string $hex
     *
     * @return string|null
     */
    public static function encodeHex(string $hex) : ?string
    {
        if (empty($hex)) return null;
        $prefix = '';
        if (strpos($hex, '-') === 0) {
            $prefix = '-';
            $hex = substr($hex, 1);
        }

        if (strlen($hex) % 2 !== 0) $hex = '0'.$hex;
        $bin = hex2bin($hex);
        return $bin ? $prefix . self::encode($bin) : null;
    }

    /**
     * 将编码还原为16进制字符串
     *
     * @param string $encode
     *
     * @return string|null
     */
    public static function decodeHex(string $encode) : ?string
    {
        $prefix = '';
        if (strpos($encode, '-') === 0) {
            $prefix = '-';
            $encode = substr($encode, 1);
        }
        return $prefix . bin2hex(self::decode($encode)) ? : null;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: hamdon
 * Date: 2019/4/26
 * Time: 19:02
 */

namespace Hamdon\Beaver\Crypto;


class DESService
{
    static $obj = null;

    public static function create()
    {
        if (self::$obj == null) {
            self::$obj = new DESService();
        }
        return self::$obj;
    }

    public function encrypt($desStr,$key){
        $desStr = $this->pkcs5Pad($desStr,8);
        if (strlen($desStr) % 8) {
            $desStr = str_pad($desStr, strlen($desStr) + 8 - strlen($desStr) % 8, "\0");
        }
        $method = 'DES-EDE3';
        return bin2hex(openssl_encrypt($desStr, $method, $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING,''));
    }

    public function decrypt($str, $key)
    {
        $str = pack("H*",$str);
        $method = 'DES-EDE3';
        $str = openssl_decrypt($str, $method, $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, '');
        return $str;
    }

    public function pkcs5Pad($text,$byteLen=8)
    {
        $pad = $byteLen - (strlen($text) % $byteLen);
        return $text . str_repeat(chr($pad), $pad);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: hamdon
 * Date: 2019/4/26
 * Time: 19:30
 */

namespace Hamdon\Beaver\Crypto;


class RSAService
{
    static $obj = null;

    public static function create()
    {
        if (self::$obj == null) {
            self::$obj = new RSAService();
        }
        return self::$obj;
    }

    /**
     * 私钥加密
     *
     * @param $data
     * @param $privatePemFile
     * @param int $isBase64
     * @return string
     */
    public static function encryptByPrivateKey($data, $privatePemFile, $isBase64 = 1)
    {
        $piKey = openssl_pkey_get_private(file_get_contents($privatePemFile));//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        $encrypted = "";
        openssl_private_encrypt($data, $encrypted, $piKey, OPENSSL_PKCS1_PADDING);//私钥加密
        if ($isBase64) {

        }
        return base64_encode($encrypted);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
    }

    /**
     * 私钥内容加密
     *
     * @param $data
     * @param $privatePemContent
     * @param int $isBase64
     * @return string
     */
    public static function encryptByPrivateKeyContent($data, $privatePemContent, $isBase64 = 1)
    {
        if(strpos('-----BEGIN RSA PRIVATE KEY-----',$privatePemContent) === false) {
            $privatePemContent = '
-----BEGIN RSA PRIVATE KEY-----
' . $privatePemContent . '
-----END RSA PRIVATE KEY-----
';
        }
        $piKey = openssl_pkey_get_private($privatePemContent);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        $encrypted = "";
        openssl_private_encrypt($data, $encrypted, $piKey, OPENSSL_PKCS1_PADDING);//私钥加密
        if ($isBase64) {

        }
        return base64_encode($encrypted);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
    }

    /**
     * 公钥加密
     *
     * @param $data
     * @param $publicPemFile
     * @param int $isBase64
     * @return string
     */
    public static function encryptByPublicKey($data, $publicPemFile, $isBase64 = 1)
    {
        $outVal = '';
        $res = openssl_pkey_get_public(file_get_contents($publicPemFile));
        openssl_public_encrypt($data, $outVal, $res);
        if ($isBase64 == 1) {
            $outVal = base64_encode($outVal);
        }
        return $outVal;
    }

    /**
     * 公钥内容加密
     *
     * @param $data
     * @param $publicPemContent
     * @param int $isBase64
     * @return string
     */
    public static function encryptByPublicKeyContent($data, $publicPemContent, $isBase64 = 1)
    {
        if(strpos('-----BEGIN PUBLIC KEY-----',$publicPemContent) === false) {
            $publicPemContent = '
-----BEGIN PUBLIC KEY-----
' . $publicPemContent . '
-----END PUBLIC KEY-----
';
        }
        $outVal = '';
        $res = openssl_pkey_get_public($publicPemContent);
        openssl_public_encrypt($data, $outVal, $res);
        if ($isBase64 == 1) {
            $outVal = base64_encode($outVal);
        }
        return $outVal;
    }

    /**
     * 公钥解密
     *
     * @param $data
     * @param $publicPemFile
     * @param int $isBase64
     * @return string
     */
    public static function decryptByPublicKey($data, $publicPemFile, $isBase64 = 1)
    {
        $decrypted = "";
        if ($isBase64 == 1) {
            $data = base64_decode($data);
        }
        $puKey = openssl_pkey_get_public(file_get_contents($publicPemFile));//这个函数可用来判断公钥是否是可用的，可用返回资源id Resource id
        openssl_public_decrypt($data, $decrypted, $puKey, OPENSSL_PKCS1_PADDING);//公钥解密
        return $decrypted;
    }

    /**
     * 公钥内容解密
     *
     * @param $data
     * @param $publicPemContent
     * @param int $isBase64
     * @return string
     */
    public static function decryptByPublicKeyContent($data, $publicPemContent, $isBase64 = 1)
    {
        $decrypted = "";
        if ($isBase64 == 1) {
            $data = base64_decode($data);
        }
        if(strpos('-----BEGIN PUBLIC KEY-----',$publicPemContent) === false) {
            $publicPemContent = '
-----BEGIN PUBLIC KEY-----
' . $publicPemContent . '
-----END PUBLIC KEY-----
';
        }
        $puKey = openssl_pkey_get_public($publicPemContent);//这个函数可用来判断公钥是否是可用的，可用返回资源id Resource id
        openssl_public_decrypt($data, $decrypted, $puKey, OPENSSL_PKCS1_PADDING);//公钥解密
        return $decrypted;
    }

    /**
     * 私钥解密
     *
     * @param $data
     * @param $privatePemFile
     * @param int $isBase64
     * @return string
     */
    public static function decryptByPrivateKey($data, $privatePemFile, $isBase64 = 1)
    {
        $outVal = '';
        if ($isBase64 == 1) {
            $data = base64_decode($data);
        }
        $res = openssl_pkey_get_private(file_get_contents($privatePemFile));//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        openssl_private_decrypt($data, $outVal, $res);
        return $outVal;
    }

    /**
     * 私钥内容解密
     *
     * @param $data
     * @param $privatePemContent
     * @param int $isBase64
     * @return string
     */
    public static function decryptByPrivateKeyContent($data, $privatePemContent, $isBase64 = 1)
    {
        $outVal = '';
        if ($isBase64 == 1) {
            $data = base64_decode($data);
        }
        if(strpos('-----BEGIN RSA PRIVATE KEY-----',$privatePemContent) === false) {
            $privatePemContent = '
-----BEGIN RSA PRIVATE KEY-----
' . $privatePemContent . '
-----END RSA PRIVATE KEY-----
';
        }
        $res = openssl_pkey_get_private($privatePemContent);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        openssl_private_decrypt($data, $outVal, $res);
        return $outVal;
    }

    /**
     * 创建一组公钥私钥
     *
     * @return array
     */
    public static function newRsaKey()
    {
        $res = openssl_pkey_new();
        openssl_pkey_export($res, $privKey);
        $d = openssl_pkey_get_details($res);
        $pubKey = $d['key'];
        return array(
            'privkey' => $privKey,
            'pubkey' => $pubKey
        );
    }
}
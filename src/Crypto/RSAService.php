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
            if(strpos($privatePemContent, "\n") === false){
                $privatePemContent = chunk_split($privatePemContent, 64, PHP_EOL);//在每一个64字符后加一个\n
                $privatePemContent = trim($privatePemContent, "\n");
            }
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
     * 私钥内容加密-新
     *
     * @param $data
     * @param $privatePemContent
     * @param int $isBase64
     * @return string
     */
    public static function encryptByPrivateKeyContentNew($data, $privatePemContent, $isBase64 = 1)
    {
        if(strpos('-----BEGIN RSA PRIVATE KEY-----',$privatePemContent) === false) {
            $privatePemContent = chunk_split($privatePemContent, 64, PHP_EOL);//在每一个64字符后加一个\n
            if(strpos($privatePemContent, "\n") === false){
                $privatePemContent = chunk_split($privatePemContent, 64, PHP_EOL);//在每一个64字符后加一个\n
                $privatePemContent = trim($privatePemContent, "\n");
            }
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
        if (strpos('-----BEGIN PUBLIC KEY-----', $publicPemContent) === false) {
            if(strpos($publicPemContent, "\n") === false){
                $publicPemContent = chunk_split($publicPemContent, 64, PHP_EOL);//在每一个64字符后加一个\n
                $publicPemContent = trim($publicPemContent, "\n");
            }
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
     * 公钥内容加密-新
     *
     * @param $data
     * @param $publicPemContent
     * @param int $isBase64
     * @return string
     */
    public static function encryptByPublicKeyContentNew($data, $publicPemContent, $isBase64 = 1,$splitSize = 0)
    {
        if(strpos('-----BEGIN PUBLIC KEY-----',$publicPemContent) === false) {
            if(strpos($publicPemContent, "\n") === false){
                $publicPemContent = chunk_split($publicPemContent, 64, PHP_EOL);//在每一个64字符后加一个\n
                $publicPemContent = trim($publicPemContent, "\n");
            }
            $publicPemContent = '
-----BEGIN PUBLIC KEY-----
' . $publicPemContent . '
-----END PUBLIC KEY-----
';
        }
        $outVal = '';
        $res = openssl_pkey_get_public($publicPemContent);
        if($splitSize > 0){
            $decrypted = array();
            $dataArray = str_split($data, $splitSize);
            foreach($dataArray as $subData){
                $subDecrypted = null;
                openssl_public_encrypt($subData, $subDecrypted, $res);
                $decrypted[] = $subDecrypted;
            }
            $outVal = implode('',$decrypted);
        }else{
            openssl_public_encrypt($data, $outVal, $res);
        }
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
            if(strpos($publicPemContent, "\n") === false){
                $publicPemContent = chunk_split($publicPemContent, 64, PHP_EOL);//在每一个64字符后加一个\n
                $publicPemContent = trim($publicPemContent, "\n");
            }
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
     * 公钥内容解密-新
     *
     * @param $data
     * @param $publicPemContent
     * @param int $isBase64
     * @return string
     */
    public static function decryptByPublicKeyContentNew($data, $publicPemContent, $isBase64 = 1,$splitSize = 0)
    {
        $decrypted = "";
        if ($isBase64 == 1) {
            $data = base64_decode($data);
        }
        if(strpos('-----BEGIN PUBLIC KEY-----',$publicPemContent) === false) {
            if(strpos($publicPemContent, "\n") === false){
                $publicPemContent = chunk_split($publicPemContent, 64, PHP_EOL);//在每一个64字符后加一个\n
                $publicPemContent = trim($publicPemContent, "\n");
            }
            $publicPemContent = '
-----BEGIN PUBLIC KEY-----
' . $publicPemContent . '
-----END PUBLIC KEY-----
';
        }
        $puKey = openssl_pkey_get_public($publicPemContent);//这个函数可用来判断公钥是否是可用的，可用返回资源id Resource id
        if($splitSize > 0){
            $sDecrypted = array();
            $dataArray = str_split($data, $splitSize);
            foreach($dataArray as $subData){
                $subDecrypted = null;
                openssl_public_encrypt($subData, $subDecrypted, $puKey);
                openssl_public_decrypt($data, $decrypted, $puKey, OPENSSL_PKCS1_PADDING);//公钥解密
                $sDecrypted[] = $subDecrypted;
            }
            $decrypted = implode('',$sDecrypted);
        }else{
            openssl_public_decrypt($data, $decrypted, $puKey, OPENSSL_PKCS1_PADDING);//公钥解密
        }
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
    public static function decryptByPrivateKeyContent($data, $privatePemContent, $isBase64 = 1,$splitSize=0)
    {
        $outVal = '';
        if ($isBase64 == 1) {
            $data = base64_decode($data);
        }
        if(strpos('-----BEGIN RSA PRIVATE KEY-----',$privatePemContent) === false) {
            if(strpos($privatePemContent, "\n") === false){
                $privatePemContent = chunk_split($privatePemContent, 64, PHP_EOL);//在每一个64字符后加一个\n
                $privatePemContent = trim($privatePemContent, "\n");
            }
            $privatePemContent = '
-----BEGIN RSA PRIVATE KEY-----
' . $privatePemContent . '
-----END RSA PRIVATE KEY-----
';
        }
        $res = openssl_pkey_get_private($privatePemContent);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        if($splitSize > 0){
            $sDecrypted = array();
            $dataArray = str_split($data, $splitSize);
            foreach($dataArray as $subData){
                $subDecrypted = null;
                openssl_private_decrypt($subData, $subDecrypted, $res);
                $sDecrypted[] = $subDecrypted;
            }
            $outVal = implode('',$sDecrypted);
        }else{
            openssl_private_decrypt($data, $outVal, $res);
        }
        return $outVal;
    }

    /**
     * 私钥内容解密-新
     *
     * @param $data
     * @param $privatePemContent
     * @param int $isBase64
     * @return string
     */
    public static function decryptByPrivateKeyContentNew($data, $privatePemContent, $isBase64 = 1)
    {
        $outVal = '';
        if ($isBase64 == 1) {
            $data = base64_decode($data);
        }
        if(strpos('-----BEGIN RSA PRIVATE KEY-----',$privatePemContent) === false) {
            if(strpos($privatePemContent, "\n") === false){
                $privatePemContent = chunk_split($privatePemContent, 64, PHP_EOL);//在每一个64字符后加一个\n
                $privatePemContent = trim($privatePemContent, "\n");
            }
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

    /**
     * pfx文件解密
     *
     * @param $encrypted
     * @param $fpxFile
     * @param string $pwd
     * @return mixed
     */
    public function decryptFromPfx($encrypted, $fpxFile, $pwd='')
    {
        $certs = array();
        openssl_pkcs12_read(file_get_contents($fpxFile), $certs, $pwd);
        openssl_private_decrypt($encrypted, $decrypted, $certs['pkey']);
        return $decrypted;
    }

    /**
     * pem 文件加密
     *
     * @param $data
     * @param $pemFile
     * @return string
     */
    public function encryptFromPem($data, $pemFile)
    {
        $pubKey = openssl_get_publickey(file_get_contents($pemFile));
        $encrypted = '';
        openssl_public_encrypt($data, $encrypted, $pubKey);
        return base64_encode($encrypted);
    }

    /**
     * 数据签名验证 -基于pem文件
     *
     * @param $data
     * @param $sign
     * @param $pemFile
     * @return int|resource
     */
    public function verifyFromPem($data, $sign, $pemFile)
    {
        $res = openssl_get_publickey(file_get_contents($pemFile));
        $details = openssl_pkey_get_details($res);
        $res = openssl_verify($data, base64_decode($sign), $details['key'], OPENSSL_ALGO_SHA1);
        return $res;
    }
}
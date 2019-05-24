# beaver
some useful class for laravel

# CacheLock

lock function :  \Hamdon\Beaver\CacheLock::lock('lock_name', 1);

unlock function : \Hamdon\Beaver\CacheLock::unLock('lock_name');

check function : \Hamdon\Beaver\CacheLock::check('lock_name');

setDriver function : \Hamdon\Beaver\CacheLock::setDriver('redis');

setDriver parameter : apc、array、database、file、memcached、redis

# InputFilter

Hamdon\Beaver\InputFilter::isId(111);

Hamdon\Beaver\InputFilter::isTrue(1==1,'your tips');

Hamdon\Beaver\InputFilter::isNotTrue(1==2,'your tips');

Hamdon\Beaver\InputFilter::isNotSet($aa,'your tips');

Hamdon\Beaver\InputFilter::isNull($aa,'your tips');

Hamdon\Beaver\InputFilter::isNotNull($aa,'your tips');

Hamdon\Beaver\InputFilter::isEmpty($aa,'your tips');

Hamdon\Beaver\InputFilter::throwAMessage('your tips');

Hamdon\Beaver\InputFilter::isPhone('13800000000');

Hamdon\Beaver\InputFilter::isAbbr('aaabd');

Hamdon\Beaver\InputFilter::isHttp('http://www.heliwebs.com');

Hamdon\Beaver\InputFilter::isIdNo('440981000000000000');

Hamdon\Beaver\InputFilter::isChineseName($cc);

Hamdon\Beaver\InputFilter::isRightPassword($cc);

Hamdon\Beaver\InputFilter::isHasChinese($cc);

Hamdon\Beaver\InputFilter::isBetWeenZeroAndOneHundred(98);

Hamdon\Beaver\InputFilter::isBankCardOne('1111111111');

Hamdon\Beaver\InputFilter::isBankCardTwo('1111111111');

Hamdon\Beaver\InputFilter::isBankCard('1111111111');

Hamdon\Beaver\InputFilter::isCarLicense('1111111111');

Hamdon\Beaver\InputFilter::isDate('2019-4-10');

Hamdon\Beaver\InputFilter::isEmail('cao4141@qq.com');

Hamdon\Beaver\InputFilter::isDateDay(20);

# ImageService

generate image thumb

要安装exif扩展

可以进到源文件目录

```
#cd ext/exif
#/usr/local/php/bin/phpize
#./configure --with-php-config=/usr/local/php/bin/php-config
#make && make install
#php --ini
#vim exif.ini
#extension=exif.so
```
```
    //生成缩略图
    $image = 'http://www.heliwebs.com/file/20190110/aaaa.png';
    $wh = getimagesize($image);
    $w = $wh[0] ?? 250;
    $h = $wh[1] ?? 500;
    $thumbnail = Hamdon\Beaver\ImageService::create()->thumb($image, intval($w / 3), intval($h / 3));
    
    //图片转base64
    $aa = '/file/20190410/aaa.png';
    $base64 = Hamdon\Beaver\ImageService::create()->base64EncodeImage($aa);
    
```

# CurlService

Hamdon\Beaver\CurlService::create()->get('http://www.heliwebs.com',["a"=>1])

Hamdon\Beaver\CurlService::create()->realGet('http://www.heliwebs.com')

Hamdon\Beaver\CurlService::create()->post('http://www.heliwebs.com',["a"=>1])

Hamdon\Beaver\CurlService::create()->realPost('http://www.heliwebs.com',["a"=>1])

Hamdon\Beaver\CurlService::create()->postJson('http://www.heliwebs.com',["a"=>1])

Hamdon\Beaver\CurlService::create()->curlUpload('http://www.heliwebs.com',["a"=>1],['/file/20190410/aaa.png','/file/20190410/bbbb.png'])


# SpreadQrService

```
$fontColor = 'rgba(0,0,0,1)';
$color = substr($fontColor,5,-1);
$wantColor = explode(',',$color);
$fontFile = public_path('images/bank_template/ztgjkai.ttf');
$qrPreviewSrc = 'images/spread_qr_product_preview';
$logoImg = '/file/20190410/aaa.png';
$textOne = 'aaaaa';
$textTwo = 'bbbbb';
$spreadSrc = SpreadQrService::create()->setFontSize($font_size)
            ->setTextColorR($wantColor[0])
            ->setTextColorG($wantColor[1])
            ->setTextColorB($wantColor[2])
            ->setTextColorA($wantColor[3])
            ->setTextOne($textOne)
            ->setTextTwo($textTwo)
            ->setFontPositionY($font_position_y)
            ->setFontPositionX($font_position_x)
            ->setTextLineNumber($text_line_number)
            ->setFontNextLineDistance($font_next_line_distance)
            ->setQrAllWidth($qr_width)
            ->setQrPositionX($qr_position_x)
            ->setQrPositionY($qr_position_y)
            ->setLogoImage($logoImg)
            ->setLogoWidth($logo_width)
            ->setLogoPositionX($logo_position_x)
            ->setLogoPositionY($logo_position_y)
            ->setWxIsRound(0)
            ->setWxImage('images/bank_template/wx.jpg')
            ->setWxAllWidth($wx_width)
            ->setWxPositionX($wx_position_x)
            ->setWxPositionY($wx_position_y)
            ->setBgImgSrc($bg_img_src)
            ->setQrImgFileName($qr_preview_src)
            ->composeQrImg();

```

# NumberService

```
//格式化阅读数

Hamdon\Beaver\NumberService::create()->formatViewNumber(111111);

//格式化时间戳

Hamdon\Beaver\NumberService::create()->formatCreatedTime(1555487954);

```

# XmlService

```
//数组转XML

Hamdon\Beaver\XmlService::create()->arrayToXml(['a'=>1]);

//将XML转为array

Hamdon\Beaver\XmlService::create()->xmlToArray('
<?xml version="1.0" encoding="ISO-8859-1"?>
<!-- Edited by XMLSpy® -->
<note>
	<to>Tove</to>
	<from>Jani</from>
	<heading>Reminder</heading>
	<body>Don't forget me this weekend!</body>
</note>
');

```

# ZipFileService


```
        $dfile =  tempnam('/tmp', 'tmp');//产生一个临时文件，用于缓存下载文件
        $zip = Hamdon\Beaver\ZipFileService::create();
        //----------------------
        $filename = 'my.zip'; //下载的默认文件名

        //以下是需要下载的图片数组信息，将需要下载的图片信息转化为类似即可
        $image = array(
            array('image_src' => 'aaa.png', 'image_name' => mb_convert_encoding('id_card_positive','UTF-8'), 1)),
            array('image_src' => 'bbb.png', 'image_name' => mb_convert_encoding('id_card_back','UTF-8'), 1)),
            array('image_src' => 'ccc.png', 'image_name' => mb_convert_encoding('bank_card_positive','UTF-8'), 1)),
            array('image_src' => 'ddd.png', 'image_name' => mb_convert_encoding('bank_card_back','UTF-8'), 1)),
            array('image_src' => 'eee.png', 'image_name' => mb_convert_encoding('id_card_hand_in','UTF-8'), 1)),
        );

        foreach($image as $v){
            $zip->add_file(file_get_contents($v['image_src']),  $v['image_name']);
            // 添加打包的图片，第一个参数是图片内容，第二个参数是压缩包里面的显示的名称, 可包含路径
            // 或是想打包整个目录 用 $zip->add_path($image_path);
        }
        //----------------------
        $zip->output($dfile);

        // 下载文件
        ob_clean();
        header('Pragma: public');
        header('Last-Modified:'.gmdate('D, d M Y H:i:s') . 'GMT');
        header('Cache-Control:no-store, no-cache, must-revalidate');
        header('Cache-Control:pre-check=0, post-check=0, max-age=0');
        header('Content-Transfer-Encoding:binary');
        header('Content-Encoding:none');
        header('Content-type:multipart/form-data');
        header('Content-Disposition:attachment; filename="'.$filename.'"'); //设置下载的默认文件名
        header('Content-length:'. filesize($dfile));
        $fp = fopen($dfile, 'r');
        while(connection_status() == 0 && $buf = @fread($fp, 8192)){
            echo $buf;
        }
        fclose($fp);
        @unlink($dfile);
        @flush();
        @ob_flush();
        exit();
        
```

# 加密解密系列

```
////DES
$str = 'test';
$key = 'aaaaaaaa';
//DES 加密
$desStr = Hamdon\Beaver\Crypto\DESService::create()->encrypt($str,$key);
//DES 解密
$newStr = Hamdon\Beaver\Crypto\DESService::create()->decrypt($desStr,$key);

////RSA
$privatePemFile = '/aaa/bbb/ccc/ddd/private_pkcs8_key.pem';
$publicPemFile = '/aaa/bbb/ccc/ddd/public_key.pem';
$willSignStr = 'bbbbbbb';
$sha256SourceSignString = hash("sha256", $willSignStr);
//RSA加密
$encrypStr = Hamdon\Beaver\Crypto\RSAService::create()->encryptByPrivateKey($sha256SourceSignString,$privatePemFile);
//RSA解密
$decryptStr = Hamdon\Beaver\Crypto\RSAService::create()->decryptByPublicKey($encrypStr,$publicPemFile);

```
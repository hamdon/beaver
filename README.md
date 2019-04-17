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

# beaver
some useful class for laravel

# CacheLock：
lock function :  \Hamdon\Beaver\CacheLock::lock('lock_name', 1);

unlock function : \Hamdon\Beaver\CacheLock::unLock('lock_name');

check function : \Hamdon\Beaver\CacheLock::check('lock_name');

setDriver function : \Hamdon\Beaver\CacheLock::setDriver('redis');

setDriver parameter : apc、array、database、file、memcached、redis

# InputFilter：
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

# ImageService：

generate image thumb
```
    $image = 'http://www.heliwebs.com/file/20190110/aaaa.png';
    $wh = getimagesize($image);
    $w = $wh[0] ?? 250;
    $h = $wh[1] ?? 500;
    $thumbnail = Hamdon\Beaver\ImageService::create()->thumb($image, intval($w / 3), intval($h / 3));
```

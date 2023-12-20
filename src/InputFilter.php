<?php
/**
 * Created by PhpStorm.
 * User: hamdon
 * Date: 2019/4/10
 * Time: 9:14
 */

namespace Hamdon\Beaver;


use Hamdon\Beaver\Exceptions\ControllerException;

class InputFilter
{

    /**
     * 判断id值是否有误
     *
     * @param $id
     * @param string $message
     * @throws ControllerException
     */
    public static function isId($id, $message = 'id有误')
    {
        if (empty($id) || !is_numeric($id) || $id < 0) {
            throw new ControllerException($message);
        }
    }

    /**
     * 判断表达式或值，是不是true
     *
     * @param $expression
     * @param $message
     * @throws ControllerException
     */
    public static function isTrue($expression, $message)
    {
        if ($expression) {
            throw new ControllerException($message);
        }
    }

    /**
     * 判断表达式或值，是不是不符合
     *
     * @param $expression
     * @param $message
     * @throws ControllerException
     */
    public static function isNotTrue($expression, $message)
    {
        if (!$expression) {
            throw new ControllerException($message);
        }
    }

    /**
     * 判断值有没有存在
     *
     * @param $expression
     * @param $message
     * @throws ControllerException
     */
    public static function isNotSet($expression, $message)
    {
        if (!isset($expression)) {
            throw  new ControllerException($message);
        }
    }

    /**
     * 判断值是不是null
     *
     * @param $expression
     * @param $message
     * @throws ControllerException
     */
    public static function isNull($expression, $message)
    {
        if ($expression == null) {
            throw new ControllerException($message);
        }
    }

    /**
     * 判断值是不是不是null
     *
     * @param $expression
     * @param $message
     * @throws ControllerException
     */
    public static function isNotNull($expression, $message)
    {
        if ($expression) {
            throw new ControllerException($message);
        }
    }

    /**
     * 判断值是不是不empty
     * @param $expression
     * @param $message
     * @throws ControllerException
     */
    public static function isEmpty($expression, $message)
    {
        if (empty($expression)) {
            throw new ControllerException($message);
        }
    }

    /**
     * 抛出一个异常
     *
     * @param $message
     * @throws ControllerException
     */
    public static function throwAMessage($message)
    {
        throw new ControllerException($message);
    }

    /**
     * 是否为手机格式
     *
     * @param $phone
     * @param string $msg
     * @throws ControllerException
     */
    public static function isPhone($phone, $msg = '电话号码格式不对')
    {
        if (!preg_match('/^1[3,4,5,6,7,8,9]{1}\d{9}$/', trim($phone))) {
            throw new ControllerException($msg);
        }
    }

    /**
     * 是否为一个简写
     *
     * @param $abbr
     * @throws ControllerException
     */
    public static function isAbbr($abbr)
    {
        if (strlen($abbr) < 2) {
            throw new ControllerException('简写要大于等于两个字符');
        }
        if (!preg_match('/^[0-9a-zA-Z]+$/', $abbr)) {
            throw new ControllerException('简写只能是数字字母组合');
        }
    }

    /**
     * 是否为http链接地址
     *
     * @param $url
     * @throws ControllerException
     */
    public static function isHttp($url)
    {
        if (!preg_match("/^(http:\/\/|https:\/\/).*$/", $url)) {
            throw new ControllerException('url不符合规范');
        }
    }

    /**
     * 是否为身份证号码
     *
     * @param $vStr
     * @param string $msg
     * @throws ControllerException
     */
    public static function isIdNo($vStr, $msg = '身份证号码不符合规范')
    {
        $vCity = array(
            '11', '12', '13', '14', '15', '21', '22',
            '23', '31', '32', '33', '34', '35', '36',
            '37', '41', '42', '43', '44', '45', '46',
            '50', '51', '52', '53', '54', '61', '62',
            '63', '64', '65', '71', '81', '82', '91'
        );
        if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) throw new ControllerException($msg);
        if (!in_array(substr($vStr, 0, 2), $vCity)) throw new ControllerException($msg);
        $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
        $vLength = strlen($vStr);
        if ($vLength == 18) {
            $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
        } else {
            $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
        }
        if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) throw new ControllerException($msg);
        if ($vLength == 18) {
            $vSum = 0;
            for ($i = 17; $i >= 0; $i--) {
                $vSubStr = substr($vStr, 17 - $i, 1);
                $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr, 11));
            }
            if ($vSum % 11 != 1) throw new ControllerException($msg);
        }
    }

    /**
     * 是否为中文内容
     *
     * @param $name
     * @throws ControllerException
     */
    public static function isChineseName($name)
    {
        $name = trim($name);
        $len = mb_strlen($name, "utf-8");
        if ($len < 2) throw new ControllerException('名字不符合规范');
        //新疆等少数民族可能有·
        if (strpos($name, '·')) {
            //将·去掉，看看剩下的是不是都是中文
            $name = str_replace("·", '', $name);
            if (!preg_match('/^[\x7f-\xff]+$/', $name)) {
                throw new ControllerException('名字不符合规范');
            }
        } else {
            if (!preg_match('/^[\x7f-\xff]+$/', $name)) {
                throw new ControllerException('名字不符合规范');
            }
        }
    }

    /**
     * 密码是否符合标准
     *
     * @param $pwd
     * @throws ControllerException
     */
    public static function isRightPassword($pwd)
    {
        if (strlen($pwd) < 6 || strlen($pwd) > 20) {
            throw new ControllerException('密码长度为6到20个字符之间');
        }
    }

    /**
     * 内容是否含有中文
     *
     * @param $str
     * @param string $tip
     * @throws ControllerException
     */
    public static function isHasChinese($str, $tip = '含有中文')
    {
        $pattern = '/[^\x00-\x80]/';
        if (preg_match($pattern, $str)) {
            throw new ControllerException($tip);
        }
    }

    /**
     * 内容是否为百分比
     *
     * @param $number
     * @param string $message
     * @return bool
     * @throws ControllerException
     */
    public static function isBetWeenZeroAndOneHundred($number, $message = '百分比数字要大于等于0，小于等于100')
    {
        if (is_numeric($number) && $number >= 0 && $number <= 100) {
            return true;
        }
        throw new ControllerException($message);
    }

    /**
     * 内容是否为一个正确的银行卡号
     *
     * @param $cardNo
     * @param string $message
     * @return bool
     * @throws ControllerException
     */
    public static function isBankCardOne($cardNo, $message = '请输入一个正确的银行卡号')
    {
        $arr_no = str_split($cardNo);
        $last_n = $arr_no[count($arr_no) - 1];
        krsort($arr_no);
        $i = 1;
        $total = 0;
        foreach ($arr_no as $n) {
            if ($i % 2 == 0) {
                $ix = $n * 2;
                if ($ix >= 10) {
                    $nx = 1 + ($ix % 10);
                    $total += $nx;
                } else {
                    $total += $ix;
                }
            } else {
                $total += $n;
            }
            $i++;
        }
        $total -= $last_n;
        $x = 10 - ($total % 10);
        if ($x == $last_n) {
            return true;
        }
        throw new ControllerException($message);
    }

    /**
     * 内容是否为一个正确的银行卡号2
     *
     * @param $cardNo
     * @param string $message
     * @return bool
     * @throws ControllerException
     */
    public static function isBankCardTwo($cardNo, $message = '请输入一张正确卡')
    {
        $arr_no = str_split($cardNo);
        $last_n = $arr_no[count($arr_no) - 1];
        krsort($arr_no);
        $i = 1;
        $total = 0;
        foreach ($arr_no as $n) {
            if ($i % 2 == 0) {
                $ix = $n * 2;
                if ($ix >= 10) {
                    $nx = 1 + ($ix % 10);
                    $total += $nx;
                } else {
                    $total += $ix;
                }
            } else {
                $total += $n;
            }
            $i++;
        }
        $total -= $last_n;
        $total *= 9;
        if ($last_n == ($total % 10)) {
            return true;
        }
        throw new ControllerException($message);
    }

    /**
     * 内容是否为一个正确的银行卡号3
     *
     * @param $cardNo
     * @return bool
     */
    public static function isBankCard($cardNo)
    {
        $cardnumber = preg_replace("/\D|\s/", "", $cardNo);
        $cardlength = strlen($cardnumber);
        if ($cardlength != 0) {
            $parity = $cardlength % 2;
            $sum = 0;
            for ($i = 0; $i < $cardlength; $i++) {
                $digit = $cardnumber[$i];
                if ($i % 2 == $parity) $digit = $digit * 2;
                if ($digit > 9) $digit = $digit - 9;
                $sum = $sum + $digit;
            }
            $valid = ($sum % 10 == 0);
            return $valid;
        }
        return false;
    }

    /**
     * 内容是否为车牌号码
     *
     * @param $license
     * @return bool
     * @throws ControllerException
     */
    public static function isCarLicense($license)
    {
        if (empty($license)) {
            return false;
        }
        /*
            #匹配民用车牌和使馆车牌
            # 判断标准
        　　# 1，第一位为汉字省份缩写
        　　# 2，第二位为大写字母城市编码
        　　# 3，后面是5位仅含字母和数字的组合
        */
        $regular = "/[京津冀晋蒙辽吉黑沪苏浙皖闽赣鲁豫鄂湘粤桂琼川贵云渝藏陕甘青宁新使]{1}[A-Z]{1}[0-9a-zA-Z]{5}$/u";
        preg_match($regular, $license, $match);
        if (isset($match[0])) {
            return true;
        }
        //#匹配特种车牌(挂,警,学,领,港,澳)
        $regular = '/[京津冀晋蒙辽吉黑沪苏浙皖闽赣鲁豫鄂湘粤桂琼川贵云渝藏陕甘青宁新]{1}[A-Z]{1}[0-9a-zA-Z]{4}[挂警学领港澳]{1}$/u';
        preg_match($regular, $license, $match);
        if (isset($match[0])) {
            return true;
        }
        //#匹配武警车牌
        $regular = '/^WJ[京津冀晋蒙辽吉黑沪苏浙皖闽赣鲁豫鄂湘粤桂琼川贵云渝藏陕甘青宁新]?[0-9a-zA-Z]{5}$/ui';
        preg_match($regular, $license, $match);
        if (isset($match[0])) {
            return true;
        }
        //#匹配军牌
        $regular = "/[A-Z]{2}[0-9]{5}$/";
        preg_match($regular, $license, $match);
        if (isset($match[0])) {
            return true;
        }
        //#匹配新能源车辆6位车牌
        $regular = "/[京津冀晋蒙辽吉黑沪苏浙皖闽赣鲁豫鄂湘粤桂琼川贵云渝藏陕甘青宁新]{1}[A-Z]{1}[DF]{1}[0-9a-zA-Z]{5}$/u";
        preg_match($regular, $license, $match);
        if (isset($match[0])) {
            return true;
        }
        //#小型新能源车
        $regular = "/[京津冀晋蒙辽吉黑沪苏浙皖闽赣鲁豫鄂湘粤桂琼川贵云渝藏陕甘青宁新]{1}[A-Z]{1}[DF]{1}[0-9a-zA-Z]{5}$/u";
        preg_match($regular, $license, $match);
        if (isset($match[0])) {
            return true;
        }
        //#大型新能源车
        $regular = "/[京津冀晋蒙辽吉黑沪苏浙皖闽赣鲁豫鄂湘粤桂琼川贵云渝藏陕甘青宁新]{1}[A-Z]{1}[0-9a-zA-Z]{5}[DF]{1}$/u";
        preg_match($regular, $license, $match);
        if (isset($match[0])) {
            return true;
        }

        throw new ControllerException('请输入正确的车牌号码');
    }

    /**
     * 内容是否为日期
     *
     * @param $date
     * @param string $msg
     * @return bool
     * @throws ControllerException
     */
    public static function isDate($date, $msg = '日期格式有误')
    {
        $is_date = strtotime($date) ? strtotime($date) : false;
        if ($is_date != false) {
            return true;
        }
        throw new ControllerException($msg);
    }

    /**
     * 内容是否为邮件格式
     *
     * @param $email
     * @param string $msg
     * @return bool
     * @throws ControllerException
     */
    public static function isEmail($email, $msg = '邮件格式有误')
    {
        $pattern = '/^[a-z0-9]+([._-][a-z0-9]+)*@([0-9a-z]+\.[a-z]{2,14}(\.[a-z]{2})?)$/i';
        if (preg_match($pattern, $email)) {
            return true;
        }
        throw new ControllerException($msg);
    }

    /**
     * 内容是否为具体日
     *
     * @param $day
     * @param string $msg
     * @return bool
     * @throws ControllerException
     */
    public static function isDateDay($day, $msg = '数字有误')
    {
        !is_numeric($day) && $day = 0;
        if ($day <= 0 || $day > 31) {
            throw new ControllerException($msg);
        }
        return true;
    }

    /**
     * 判断是否全是数字
     *
     * @param $str
     * @param string $msg
     * @return bool
     * @throws ControllerException
     */
    public static function isAllNumber($str, $msg = '不是全数字')
    {
        $pattern = '/^\d+(\d+)?$/';
        if (!preg_match($pattern, $str)) {
            throw new ControllerException($msg);
        }
        return true;
    }

    /**
     * 判断是不是中国移动手机号码
     *
     * @param $str
     * @return bool
     */
    public static function isChinaMobile($str)
    {
        $pattern = '/^134[0-8]\\d{7}$|^(?:13[5-9]|147|15[0-27-9]|178|1703|1705|1706|18[2-478])\\d{7,8}$/';
        if (!preg_match($pattern, $str)) {
            return false;
        }
        return true;
    }

    /**
     * 判断是不是中国联通手机号码
     *
     * @param $str
     * @return bool
     */
    public static function isChinaUniom($str)
    {
        $pattern = '/^(?:13[0-2]|145|15[56]|176|1704|1707|1708|1709|171|18[56])\\d{7,8}|$/';
        if (!preg_match($pattern, $str)) {
            return false;
        }
        return true;
    }

    /**
     * 判断是不是中国电信手机号码
     *
     * @param $str
     * @return bool
     */
    public static function isChinaTelcom($str)
    {
        $pattern = '/^(?:13[0-2]|145|15[56]|176|1704|1707|1708|1709|171|18[56])\\d{7,8}|$/';
        if (!preg_match($pattern, $str)) {
            return false;
        }
        return true;
    }

    /**
     * 判断是不是联系电话验证（手机和固话）
     *
     * @param $str
     * @param string $msg
     * @return bool
     * @throws ControllerException
     */
    public static function isTelAndMob($str, $msg = '联系电话有误')
    {
        $isMob = "/^1[3-5,8]{1}[0-9]{9}$/";
        $isTel = "/^([0-9]{3,4}-)?[0-9]{7,8}$/";
        if (!preg_match($isMob, $str) && !preg_match($isTel, $str)) {
            throw new ControllerException($msg);
        }
        return true;
    }

    /**
     * 判断是不是统一社会信用代码
     *
     * @param $str
     * @param string $msg
     * @return bool
     * @throws ControllerException
     */
    public static function isUniformSocialCreditCode($str, $msg='统一社会信用代码有误')
    {
        $pattern = '/^[^_IOZSVa-z\W]{2}\d{6}[^_IOZSVa-z\W]{10}$/';
        if (preg_match($pattern, $str)) {
            return true;
        }
        throw new ControllerException($msg);
    }

    /**
     * 判断是不是坐标值(经度或纬度)
     *
     * @param $lngOrLatValue
     * @param string $msg
     * @return bool
     * @throws ControllerException
     */
    function isCoordinateValue($lngOrLatValue,$msg='坐标值有误') {
        // 使用正则表达式匹配经度或纬度的格式，例如：-90.0 到 90.0 或 -180.0 到 180.0
        $pattern = '/^[-+]?((90(\.0+)?)|([1-8]?\d(\.\d+)?))$/';
        if(preg_match($pattern, $lngOrLatValue)){
            return true;
        }
        throw new ControllerException($msg);
    }
}
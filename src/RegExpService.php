<?php
/**
 * Created by PhpStorm.
 * User: hamdon
 * Date: 2020/2/26
 * Time: 9:57
 */

namespace Hamdon\Beaver;


class RegExpService
{
    /**
     * 至少包括字母数字特殊字符中任意2种的正则表达式 ,并长度8到20位
     *
     * @param $pwd
     * @param int $pwdLen
     * @return bool
     */
    public static function checkPwd($pwd, $pwdLen = 8)
    {
        $reg = "/(?!^(\d+|[a-zA-Z]+|[~!@#$%^&*?]+)$)^[\w~!@#$%^&*?]{" . $pwdLen . ",20}$/";
        if (preg_match($reg, $pwd)) {
            return true;
        }
        return false;
    }
}
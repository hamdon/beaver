<?php
/**
 * Created by PhpStorm.
 * User: hamdon
 * Date: 2019/4/17
 * Time: 15:54
 */

namespace Hamdon\Beaver;

use Carbon\Carbon;

class NumberService
{
    static $obj = null;

    public static function create()
    {
        if (self::$obj == null) {
            self::$obj = new NumberService();
        }
        return self::$obj;
    }

    public function formatViewNumber($number = 0)
    {
        if ($number > 10000) {
            $len = strlen($number);
            $thirdNumber = substr($number, -4, 1);
            if ($thirdNumber == 0) {
                $number = substr($number, 0, $len - 4) . 'w';
            } else {
                $number = substr($number, 0, $len - 3);
                $number = ($number / 10) . 'w';
            }

        }
        return $number;
    }

    public function formatCreatedTime($time)
    {
        $todayTime = Carbon::today();
        $yesterday = Carbon::yesterday();
        $timeFormat = date('m-d', $time);
        if ($todayTime < $time) {
            $timeFormat = '今天';
        }
        if ($todayTime > $time && $time < $yesterday) {
            $timeFormat = '昨天';
        }
        return $timeFormat;
    }
}
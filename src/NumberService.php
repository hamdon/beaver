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

    /**
     * 产品手续费向下取整
     *
     * @param $fee
     * @param $digits
     * @return string
     */
    public function productFee($fee, $digits)
    {
        $powValue = pow(10, $digits);
        $willFee = ceil($fee * $powValue);
        return sprintf('%.' . $digits . 'f', $willFee / $powValue);
    }

    /**
     * 过滤时间格式-调整为正确的时间
     *
     * @param $dayTime
     * @return string
     */
    public function dateTimeFilter($dayTime)
    {
        if (strlen($dayTime) != 19) {
            $days = explode(' ', $dayTime);
            //过滤年月日
            if (strlen($days[0]) != 10) {
                $dates = explode('-', $days[0]);
                //过滤年份
                if (strlen($dates[0]) != 4) {
                    if (substr($dates[0], 0, 1) == '2') {
                        $dates[0] = substr($dates[0], 0, 4);
                    } else {
                        $dates[0] = substr($dates[0], -4);
                    }
                }
                //过滤月份
                if (strlen($dates[1]) != 2) {
                    $month = intval($dates[1]);
                    if (strlen($month) != 2) {
                        if (strlen($month) == 1) {
                            $dates[1] = '0' . $month;
                        } else {
                            $dates[1] = substr($month, 0, 2);
                        }
                    } else {
                        $dates[1] = $month;
                    }
                    if ($dates[1] > 12) {
                        $dates[1] = 12;
                    }
                }
                //过滤天
                if (strlen($dates[2]) != 2) {
                    $day = intval($dates[2]);
                    if (strlen($day) != 2) {
                        if (strlen($day) == 1) {
                            $dates[2] = '0' . $day;
                        } elseif (strlen($day) > 2) {
                            $dates[2] = substr($day, 0, 2);
                        } else {
                            $dates[2] = $day;
                        }
                    } else {
                        $dates[2] = $day;
                    }
                    if ($dates[2] > 31) {
                        $dates[2] = 31;
                    }
                }
                $days[0] = implode('-', $dates);
            }
            //过滤时分秒
            if (strlen($days[1] != 8)) {
                $times = explode(':', $days[1]);
                //过滤小时
                if (strlen($times[0]) != 2) {
                    $mTime = intval($times[0]);
                    if (strlen($mTime) > 2) {
                        $mTime = substr($mTime, 0, 2);
                    } elseif (strlen($mTime) == 1) {
                        $mTime = '0' . $mTime;
                    }
                    if ($mTime > 23) {
                        $mTime = 23;
                    }
                    $times[0] = $mTime;
                }
                //过滤分钟
                if (strlen($times[1]) != 2) {
                    $hTime = intval($times[1]);
                    if (strlen($hTime) > 2) {
                        $hTime = substr($hTime, 0, 2);
                    } elseif (strlen($hTime) == 1) {
                        $hTime = '0' . $hTime;
                    }
                    if ($hTime > 59) {
                        $hTime = 59;
                    }
                    $times[1] = $hTime;
                }
                //过滤秒
                if (strlen($times[2]) != 2) {
                    $sTime = intval($times[2]);
                    if (strlen($sTime) > 2) {
                        $sTime = substr($sTime, 0, 2);
                    } elseif (strlen($sTime) == 1) {
                        $sTime = '0' . $sTime;
                    }
                    if ($sTime > 59) {
                        $sTime = 59;
                    }
                    $times[2] = $sTime;
                }
                $days[1] = implode(':', $times);
            }
            //整合
            $dayTime = implode(' ', $days);
        }
        return $dayTime;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: hamdon
 * Date: 2024/4/19
 * Time: 16:15
 */

namespace Hamdon\Beaver;

class HCoordinateService
{
   
  static $obj = null;
  
  public static function create()
  {
      if (self::$obj == null) {
          self::$obj = new HCoordinateService();
      }
      return self::$obj;
  }

    /**
     * 根据经纬度计算范围(米)
     * 
     * @param $lat1
     * @param $lng1
     * @param $lat2
     * @param $lng2
     * @return float
     */
    public function twoCoordinateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6378138;
        // 近似地球半径米          				// 转换为弧度
        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;
        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;           // 使用半正矢公式  用尺规来计算
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return round($calculatedDistance);
    }
}
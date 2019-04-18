<?php
/**
 * Created by PhpStorm.
 * User: hamdon
 * Date: 2019/4/18
 * Time: 15:19
 */

namespace Hamdon\Beaver;


class XmlService
{
    static $obj = null;

    public static function create()
    {
        if (self::$obj == null) {
            self::$obj = new XmlService();
        }
        return self::$obj;
    }

    /**
     * 数组转XML
     *
     * @param $arr
     * @return string
     */
    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 将XML转为array
     *
     * @param $xml
     * @return mixed
     */
    public function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }
}
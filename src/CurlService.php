<?php
/**
 * Created by PhpStorm.
 * User: hamdon
 * Date: 2019/4/10
 * Time: 10:53
 */

namespace Hamdon\Beaver;

use CURLFile;

class CurlService
{
    static $obj = null;

    public static function create()
    {
        if (self::$obj == null) {
            self::$obj = new CurlService();
        }
        return self::$obj;
    }

    /**
     * get 请求
     *
     * @param $url  网址
     * @param null $data  要带的数据
     * @param null $header 头部
     * @param null $userAgent  UA
     * @return mixed
     */
    public function get($url, $data = null, $header = null, $userAgent = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 100);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);
        $ssl = substr($url, 0, 8) == "https://" ? true : false;
        if ($ssl) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        if (!empty($header)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        if (!empty($userAgent)) {
            curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
        }
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

    /**
     * get 请求1
     *
     * @param $url  网址
     * @param int $timeout 超时秒
     * @param bool $json 是否json返回
     * @return mixed
     */
    public function realGet($url, $timeout = 120, $json = false)
    {
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        if ($json) {
            return json_decode($output, true);
        }
        return $output;
    }

    /**
     * post 请求
     *
     * @param $url 网址
     * @param $post 内容
     * @param int $timeout 超时秒
     * @param bool $json 是否json返回
     * @param bool $Multipart 是否多文件上传
     * @param null $headers  头部 数组
     * @return mixed|string
     */
    public function post($url, $post, $timeout = 120, $json = false, $Multipart = false, $headers = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        if ($headers != null) {
            $headerArray = array();
            foreach ($headers as $key => $value) {
                array_push($headerArray, $key . ":" . $value);
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
        }

        if ($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($post) ? http_build_query($post) : $post);
        }

        if ($Multipart) {
            curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
            curl_setopt($curl, CURLOPT_POST, 1);
            $file_name = str_replace("%3A", ":", $post["_file"]);
            $file_name = str_replace("%2F", "/", $file_name);
            //echo $file_name;
            // 从php5.5开始,反对使用"@"前缀方式上传,可以使用CURLFile替代;
            // 据说php5.6开始移除了"@"前缀上传的方式
            if (class_exists('CURLFile')) {
                $file = new CURLFile($file_name);
                // 禁用"@"上传方法,这样就可以安全的传输"@"开头的参数值
                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
            } else {
                $file = "@{$file_name}";
            }

            $fields = $post;
            $fields ['_file'] = $file;

            curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);

        }
        $TLS = substr($url, 0, 8) == "https://" ? true : false;
        if ($TLS) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        }

        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if ($json) {
            return json_decode($data, true);
        }
        return $data;
    }

    /**
     * post 请求1
     *
     * @param $url 网址
     * @param $post 内容
     * @param bool $json 是否json返回
     * @return bool|mixed
     */
    public function realPost($url, $post, $json = false)
    {
        if (empty($url) || empty($post)) {
            return false;
        }

        $postData = http_build_query($post);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // stop verifying certificate
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $r = curl_exec($curl);

        curl_close($curl);
        if ($json) {
            return json_decode($r, true);
        }
        return $r;
    }

    /**
     * post 请求2
     *
     * @param $url 网址
     * @param $post 内容
     * @param int $timeout 超时秒
     * @param bool $json 是否json返回
     * @param bool $Multipart 是否多文件上传
     * @param null $headers 头部 数组
     * @return mixed|string
     */
    public function postJson($url, $post, $timeout = 120, $json = false, $Multipart = false, $headers = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
//            curl_setopt($curl, CURLOPT_USERAGENT, 'Today`s Card Technical Department');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        if ($headers != null) {
            $headerArray = array();
            foreach ($headers as $key => $value) {
                array_push($headerArray, $key . ":" . $value);
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
        }

        if ($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($post) ? json_encode($post) : $post);
        }

        if ($Multipart) {
            curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
            curl_setopt($curl, CURLOPT_POST, 1);
            $file_name = str_replace("%3A", ":", $post["_file"]);
            $file_name = str_replace("%2F", "/", $file_name);
            //echo $file_name;
            // 从php5.5开始,反对使用"@"前缀方式上传,可以使用CURLFile替代;
            // 据说php5.6开始移除了"@"前缀上传的方式
            if (class_exists('CURLFile')) {
                $file = new CURLFile($file_name);
                // 禁用"@"上传方法,这样就可以安全的传输"@"开头的参数值
                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
            } else {
                $file = "@{$file_name}";
            }

            $fields = $post;
            $fields ['_file'] = $file;

            curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);

        }
        $TLS = substr($url, 0, 8) == "https://" ? true : false;
        if ($TLS) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        }

        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if ($json) {
            return json_decode($data, true);
        }
        return $data;
    }

    /**
     * CURL 上传文件
     *
     * @param $url 处理上传文件的url
     * @param array $post_data post 传递的参数
     * @param array $file_fields 上传文件的参数，支持多个文件上传
     * @param int $timeout 请求超时时间
     * @return array|bool
     */
    function curlUpload($url, $post_data=array(), $file_fields=array(), $timeout=600) {
        $result = array('errno' => 0, 'errmsg' => '', 'result' => '');

        $ch = curl_init();
        //set various curl options first

        // set url to post to
        curl_setopt($ch, CURLOPT_URL, $url);

        // return into a variable rather than displaying it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //set curl function timeout to $timeout
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        //curl_setopt($ch, CURLOPT_VERBOSE, true);

        //set method to post
        curl_setopt($ch, CURLOPT_POST, true);

        // disable Expect header
        // hack to make it working
        $headers = array("Expect: ");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        //generate post data
        $post_array = array();
        if (!is_array($post_data)) {
            $result['errno'] = 5;
            $result['errmsg'] = 'Params error.';
            return $result;
        }

        foreach ($post_data as $key => $value) {
            $post_array[$key] = $value;
        }

        // set multipart form data - file array field-value pairs
        if(version_compare(PHP_VERSION, '5.5.0') >= 0) {
            if (!empty($file_fields)) {
                foreach ($file_fields as $key => $value) {
                    if (strpos(PHP_OS, "WIN") !== false) {
                        $value = str_replace("/", "\\", $value); // win hack
                    }
                    $file_fields[$key] = new CURLFile($value);
                }
            }
        } else {
            if (!empty($file_fields)) {
                foreach ($file_fields as $key => $value) {
                    if (strpos(PHP_OS, "WIN") !== false) {
                        $value = str_replace("/", "\\", $value); // win hack
                    }
                    $file_fields[$key] = "@" . $value;
                }
            }
        }

        // set post data
        $result_post = array_merge($post_array, $file_fields);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $result_post);
        // print_r($result_post);

        //and finally send curl request
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            curl_close($ch);
            return false;
        } else {
            curl_close($ch);
            return $output;
        }
    }
}
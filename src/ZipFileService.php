<?php
/**
 * Created by PhpStorm.
 * User: hamdon
 * Date: 2019/4/18
 * Time: 15:21
 */

namespace Hamdon\Beaver;


class ZipFileService
{
    static $obj = null;
    private $data_sec = array();
    private $ctrl_dir = array();
    private $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
    private $old_offset = 0;

    public static function create()
    {
        if (self::$obj == null) {
            self::$obj = new XmlService();
        }
        return self::$obj;
    }

    /**
     * @param int $unix_time
     * @return int
     */
    public function unix2_dos_time($unix_time = 0)
    {
        $timeArray = ($unix_time == 0) ? getdate() : getdate($unix_time);
        if ($timeArray ['year'] < 1980) {
            $timeArray ['year'] = 1980;
            $timeArray ['mon'] = 1;
            $timeArray ['mday'] = 1;
            $timeArray ['hours'] = 0;
            $timeArray ['minutes'] = 0;
            $timeArray ['seconds'] = 0;
        }
        return (($timeArray ['year'] - 1980) << 25) | ($timeArray ['mon'] << 21) | ($timeArray ['mday'] << 16) | ($timeArray ['hours'] << 11) | ($timeArray ['minutes'] << 5) | ($timeArray ['seconds'] >> 1);
    }

    /**
     *
     * @param $data
     * @param $name
     * @param int $time
     */
    function add_file($data, $name, $time = 0)
    {
        $name = str_replace('\\', '/', $name);

        $dtime = dechex($this->unix2_dos_time($time));
        $hexdtime = '\x' . $dtime [6] . $dtime [7] . '\x' . $dtime [4] . $dtime [5] . '\x' . $dtime [2] . $dtime [3] . '\x' . $dtime [0] . $dtime [1];
        eval('$hexdtime = "' . $hexdtime . '";');

        $fr = "\x50\x4b\x03\x04";
        $fr .= "\x14\x00";
        $fr .= "\x00\x00";
        $fr .= "\x08\x00";
        $fr .= $hexdtime;

        $unc_len = strlen($data);
        $crc = crc32($data);
        $zdata = gzcompress($data);
        $zdata = substr(substr($zdata, 0, strlen($zdata) - 4), 2);
        $c_len = strlen($zdata);
        $fr .= pack('V', $crc);
        $fr .= pack('V', $c_len);
        $fr .= pack('V', $unc_len);
        $fr .= pack('v', strlen($name));
        $fr .= pack('v', 0);
        $fr .= $name;

        $fr .= $zdata;
        $fr .= pack('V', $crc);
        $fr .= pack('V', $c_len);
        $fr .= pack('V', $unc_len);

        $this->data_sec [] = $fr;

        $cdrec = "\x50\x4b\x01\x02";
        $cdrec .= "\x00\x00";
        $cdrec .= "\x14\x00";
        $cdrec .= "\x00\x00";
        $cdrec .= "\x08\x00";
        $cdrec .= $hexdtime;
        $cdrec .= pack('V', $crc);
        $cdrec .= pack('V', $c_len);
        $cdrec .= pack('V', $unc_len);
        $cdrec .= pack('v', strlen($name));
        $cdrec .= pack('v', 0);
        $cdrec .= pack('v', 0);
        $cdrec .= pack('v', 0);
        $cdrec .= pack('v', 0);
        $cdrec .= pack('V', 32);

        $cdrec .= pack('V', $this->old_offset);
        $this->old_offset += strlen($fr);

        $cdrec .= $name;

        $this->ctrl_dir[] = $cdrec;
    }

    /**
     * @param $path
     * @param int $l
     */
    function add_path($path, $l = 0)
    {
        $d = @opendir($path);
        $l = $l > 0 ? $l : strlen($path) + 1;
        while ($v = @readdir($d)) {
            if ($v == '.' || $v == '..') {
                continue;
            }
            $v = $path . '/' . $v;
            if (is_dir($v)) {
                $this->add_path($v, $l);
            } else {
                $this->add_file(file_get_contents($v), substr($v, $l));
            }
        }
    }

    /**
     * @return string
     */
    function file()
    {
        $data = implode('', $this->data_sec);
        $ctrldir = implode('', $this->ctrl_dir);
        return $data . $ctrldir . $this->eof_ctrl_dir . pack('v', sizeof($this->ctrl_dir)) . pack('v', sizeof($this->ctrl_dir)) . pack('V', strlen($ctrldir)) . pack('V', strlen($data)) . "\x00\x00";
    }

    /**
     * @param $files
     */
    function add_files($files)
    {
        foreach ($files as $file) {
            if (is_file($file)) {
                $data = implode("", file($file));
                $this->add_file($data, $file);
            }
        }
    }

    /**
     * @param $file
     */
    function output($file)
    {
        $fp = fopen($file, "w");
        fwrite($fp, $this->file());
        fclose($fp);
    }
}
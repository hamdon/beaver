<?php
/**
 * Created by PhpStorm.
 * User: hamdon
 * Date: 2019/4/10
 * Time: 10:38
 */

namespace Hamdon\Beaver;


class ImageService
{
    static $obj = null;
    private $error;
    private $src;
    private $image;
    private $imageInfo;
    private $percent = 0.5;

    public static function create()
    {
        if (self::$obj == null) {
            self::$obj = new ImageService();
        }
        return self::$obj;
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * 剪裁图片
     *
     * @param $sourceImg 图片链接地址
     * @param int $max_w  宽度
     * @param int $max_h  高度
     * @param string $prefix  文件前缀
     * @param bool $flag  是否等比缩放
     * @return string
     */
    public function thumb($sourceImg, $max_w = 250, $max_h = 500, $prefix = 'thumbnail_', $flag = true)
    {
        //获取文件的后缀
        $ext = strtolower(strrchr($sourceImg, '.'));
        $paths = parse_url($sourceImg);
        $fileType = exif_imagetype(public_path($paths['path']));
        //判断文件格式
        switch ($fileType) {
            case 1:
                $type = 'gif';
                break;
            case 2:
                $type = 'jpeg';
                break;
            case 3:
                $type = 'png';
                break;
            case 6:
                $type = 'bmp';
                break;
            default:
                $this->error = '文件格式不正确';
                return false;
        }
        //拼接打开图片的函数
        $open_fn = 'imagecreatefrom' . $type;
        //打开源图
        $src = $open_fn(public_path($paths['path']));
        //创建目标图
        $dst = imagecreatetruecolor($max_w, $max_h);
        $bg = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $bg);
        //源图的宽
        $src_w = imagesx($src);
        //源图的高
        $src_h = imagesy($src);

        //是否等比缩放
        if ($flag) { //等比
            /*            //求目标图片的宽高
                        if ($max_w/$max_h <= $src_w/$src_h) {
                            //横屏图片以宽为标准
                            $dst_w = $max_w;
                            $dst_h = $max_w * $src_h/$src_w;
                        }else{
                            //竖屏图片以高为标准
                            $dst_h = $max_h;
                            $dst_w = $max_h * $src_w/$src_h;
                        }*/
            $dst_w = $max_w;
            $dst_h = $max_w * $src_h / $src_w;
            //在目标图上显示的位置
            $dst_x = (int)(($max_w - $dst_w) / 2);
            $dst_y = (int)(($max_h - $dst_h) / 2);
        } else {    //不等比
            $dst_x = 0;
            $dst_y = 0;
            $dst_w = $max_w;
            $dst_h = $max_h;
        }
        //生成缩略图
        imagecopyresampled($dst, $src, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        //文件名
        $filename = basename(public_path($paths['path']));
        //文件夹名
        $foldername = substr(dirname(public_path($paths['path'])), 0);
        $saveFolderName = substr(dirname($paths['path']), 0);
        //缩略图存放路径
        $thumb_path = $foldername . '/' . $prefix . $filename;

        //把缩略图上传到指定的文件夹
        imagepng($dst, $thumb_path);
        //销毁图片资源
        imagedestroy($dst);
        imagedestroy($src);

        //返回新的缩略图的文件名
        return $saveFolderName . '/' . $prefix . $filename;
    }

    function base64EncodeImage($image_file)
    {
        $image_info = getimagesize($image_file);
        $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split($this->trimAll(base64_encode($image_data)));
        return $base64_image;
    }

    function base64EncodeImageForMimer($image_file)
    {
        $image_info = getimagesize($image_file);
        $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . $this->trimAll(base64_encode($image_data));
        return $base64_image;
    }

    function base64EncodeImageNoDataHeader($image_file)
    {
        $imgBinary = fread(fopen($image_file, "r"), filesize($image_file));
        return base64_encode($imgBinary);
    }

    function trimAll($str){
        $search=array(" ","　","\t","\n","\r");
        return str_replace($search, '', $str);
    }

    public function setSrc($src){
        $this->src = $src;
        return $this;
    }

    public function setPercent($percent=1)
    {
        $this->percent = $percent;
        return $this;
    }

    /** 高清压缩图片
     * @param string $saveName  提供图片名（可不带扩展名，用源图扩展名）用于保存。或不提供文件名直接显示
     */
    public function compressImg($saveName='')
    {
        $this->_openImage();
        if (!empty($saveName)) {
            $this->_saveImage($saveName);  //保存
        } else {
            $this->_showImage();
        }
    }

    /**
     * 内部：打开图片
     */
    private function _openImage()
    {
        list($width, $height, $type, $attr) = getimagesize($this->src);
        $this->imageInfo = array(
            'width'=>$width,
            'height'=>$height,
            'type'=>image_type_to_extension($type,false),
            'attr'=>$attr
        );
        $fun = "imagecreatefrom".$this->imageInfo['type'];
        $this->image = $fun($this->src);
        $this->_thumpImage();
    }

    /**
     * 内部：操作图片
     */
    private function _thumpImage()
    {
        $new_width = $this->imageInfo['width'] * $this->percent;
        $new_height = $this->imageInfo['height'] * $this->percent;
        $image_thump = imagecreatetruecolor($new_width,$new_height);
        //将原图复制带图片载体上面，并且按照一定比例压缩,极大的保持了清晰度
        imagecopyresampled($image_thump,$this->image,0,0,0,0,$new_width,$new_height,$this->imageInfo['width'],$this->imageInfo['height']);
        imagedestroy($this->image);
        $this->image = $image_thump;
    }

    /**
     * 输出图片:保存图片则用saveImage()
     */
    private function _showImage()
    {
        header('Content-Type: image/'.$this->imageInfo['type']);
        $funcs = "image".$this->imageInfo['type'];
        $funcs($this->image);
    }

    /**
     * 保存图片到硬盘：
     *
     * @param $dstImgName  1、可指定字符串不带后缀的名称，使用源图扩展名 。2、直接指定目标图片名带扩展名。
     * @return bool
     */
    private function _saveImage($dstImgName)
    {
        if(empty($dstImgName)) return false;
        $allowImgs = ['.jpg', '.jpeg', '.png', '.bmp', '.wbmp','.gif'];   //如果目标图片名有后缀就用目标图片扩展名 后缀，如果没有，则用源图的扩展名
        $dstExt =  strrchr($dstImgName ,".");
        $sourseExt = strrchr($this->src ,".");
        if(!empty($dstExt)) $dstExt =strtolower($dstExt);
        if(!empty($sourseExt)) $sourseExt =strtolower($sourseExt);
        //有指定目标名扩展名
        if(!empty($dstExt) && in_array($dstExt,$allowImgs)){
            $dstName = $dstImgName;
        }elseif(!empty($sourseExt) && in_array($sourseExt,$allowImgs)){
            $dstName = $dstImgName.$sourseExt;
        }else{
            $dstName = $dstImgName.$this->imageInfo['type'];
        }
        $funcs = "image".$this->imageInfo['type'];
        $funcs($this->image,$dstName);
    }

    /**
     * 销毁图片
     */
    public function __destruct(){
        imagedestroy($this->image);
    }
}
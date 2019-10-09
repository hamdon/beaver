<?php
/**
 * Created by PhpStorm.
 * User: hamdon
 * Date: 2019/4/10
 * Time: 11:10
 */

namespace Hamdon\Beaver;


class SpreadQrService
{
    static $obj = null;
    private $isWx = 0;
    private $wxImg = '';
    private $wxImgSource = 0;
    private $wxWidth = 387;
    private $wxHeight = 387;
    private $wxPositionX = 306;
    private $wxPositionY = 660;
    private $textOne = "龙.hamdon";
    private $textTwo = "15900000000";
    private $textLineNumber = 2;
    private $qrSrc = "";
    private $qrImgFileName = '';
    private $fontSize = 40;
    private $fontPositionY = 1250;
    private $fontPositionX = 0;
    private $fontNextLineDistance = 75;
    private $qrWidth = 387;
    private $qrHeight = 387;
    private $qrPositionX = 306;
    private $qrPositionY =  660;
    private $logoWidth = 0;
    private $logoPositionX = 0;
    private $logoPositionY = 0;
    private $logoSrc = "";
    private $bgImgSrc =  '';
    private $wxIsRound = 1;
    private $textContentType = 2;
    private $font_color_r = 0;
    private $font_color_g = 0;
    private $font_color_b = 0;
    private $font_color_a = 1;
    private $sWidth=640;
    private $sHeight=0;
    private $fontFile = '';

    public static function create()
    {
        if (self::$obj == null) {
            self::$obj = new SpreadQrService();
        }
        return self::$obj;
    }

    public function setFontFile($file){
        $this->fontFile = $file;
        return $this;
    }
    public function setLogoImage($value)
    {
        $this->logoSrc = $value;
        return $this;
    }

    public function setLogoWidth($value)
    {
        $this->logoWidth = $value;
        return $this;
    }

    public function setLogoPositionX($value)
    {
        $this->logoPositionX = $value;
        return $this;
    }

    public function setLogoPositionY($value)
    {
        $this->logoPositionY = $value;
        return $this;
    }

    public function setSWidth($sWidth=640){
        $this->sWidth = $sWidth;
        return $this;
    }

    public function setSHeight($sHeight=640){
        $this->sHeight = $sHeight;
        return $this;
    }

    public function setTextColorR($value){
        $this->font_color_r = $value;
        return $this;
    }

    public function setTextColorG($value){
        $this->font_color_g = $value;
        return $this;
    }

    public function setTextColorB($value){
        $this->font_color_b = $value;
        return $this;
    }

    public function setTextColorA($value){
        $this->font_color_a = $value;
        return $this;
    }

    public function setTextContentType($textContentType=2)
    {
        $this->textContentType = $textContentType;
        return $this;
    }

    public function setWxIsRound($isRound=1)
    {
        $this->wxIsRound = $isRound;
        return $this;
    }

    public function setTextOne($textOne)
    {
        $this->textOne = $textOne;
        return $this;
    }

    public function setTextTwo($textTwo)
    {
        $this->textTwo = $textTwo;
        return $this;
    }

    public function setTextLineNumber($textLineNumber)
    {
        $this->textLineNumber = $textLineNumber;
        return $this;
    }

    public function setQrSrc($qrSrc)
    {
        $this->qrSrc = $qrSrc;
        return $this;
    }

    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;
        return $this;
    }

    public function setFontPositionY($fontPositionY)
    {
        $this->fontPositionY = $fontPositionY;
        return $this;
    }

    public function setFontPositionX($fontPositionX)
    {
        $this->fontPositionX = $fontPositionX;
        return $this;
    }

    public function setFontNextLineDistance($fontNextLineDistance)
    {
        $this->fontNextLineDistance = $fontNextLineDistance;
        return $this;
    }

    public function setWxWidth($wxWidth){
        $this->isWx = 1;
        $this->wxWidth = $wxWidth;
        return $this;
    }

    public function setWxHeight($wxHeight)
    {
        $this->isWx = 1;
        $this->wxHeight = $wxHeight;
        return $this;
    }

    public function setWxAllWidth($width)
    {
        $this->isWx = 1;
        $this->wxHeight = $width;
        $this->wxWidth = $width;
        return $this;
    }

    public function setWxPositionX($qrPositionX)
    {
        $this->isWx = 1;
        $this->wxPositionX = $qrPositionX;
        return $this;
    }

    public function setWxPositionY($qrPositionY)
    {
        $this->isWx = 1;
        $this->wxPositionY = $qrPositionY;
        return $this;
    }

    public function setWxImage($img){
        $this->wxImg = $img;
        return $this;
    }

    public function setWxImageSource($source){
        $this->wxImgSource = $source;
        return $this;
    }

    public function setQrWidth($qrWidth)
    {
        $this->qrWidth = $qrWidth;
        return $this;
    }

    public function setQrHeight($qrHeight)
    {
        $this->qrHeight = $qrHeight;
        return $this;
    }

    public function setQrAllWidth($width)
    {
        $this->qrHeight = $width;
        $this->qrWidth = $width;
        return $this;
    }

    public function setQrPositionX($qrPositionX)
    {
        $this->qrPositionX = $qrPositionX;
        return $this;
    }

    public function setQrPositionY($qrPositionY)
    {
        $this->qrPositionY = $qrPositionY;
        return $this;
    }

    public function setBgImgSrc($bgImgSrc)
    {
        $this->bgImgSrc = $bgImgSrc;
        return $this;
    }

    public function setQrImgFileName($qrImgFileName)
    {
        $this->qrImgFileName = $qrImgFileName;
        $fileInfos = pathinfo($qrImgFileName);
        if(isset($fileInfos['extension'])){
            $this->qrImgFileName = $fileInfos['dirname'].'/'.$fileInfos['filename'];
        }
        return $this;
    }

    public function composeQrImg()
    {
        if (empty($this->bgImgSrc)) {
            return '';
        }
        $paths = parse_url($this->bgImgSrc);
        $bg = null;
        $fileType = exif_imagetype(public_path($paths['path']));
        switch ($fileType) {
            case 1:
                $bg = imagecreatefromgif(public_path($paths['path']));
                break;
            case 2:
                $bg = imagecreatefromjpeg(public_path($paths['path']));
                break;
            case 6:
                $bg = imagecreatefromwbmp(public_path($paths['path']));
                break;
            case 3:
                $bg = imagecreatefrompng(public_path($paths['path']));
                break;
        }
        $paths = parse_url($this->qrSrc);
        $imgExt = exif_imagetype(public_path($paths['path']));
        $qrCode = null;
        switch ($imgExt) {
            case 1:
                $qrCode = imagecreatefromgif(public_path($paths['path']));
                break;
            case 2:
                $qrCode = imagecreatefromjpeg(public_path($paths['path']));
                break;
            case 6:
                $qrCode = imagecreatefromwbmp(public_path($paths['path']));
                break;
            case 3:
                $qrCode = imagecreatefrompng(public_path($paths['path']));
                break;
        }
        //增加二维码
        imagecopyresampled($bg, $qrCode, $this->qrPositionX, $this->qrPositionY, 0, 0, $this->qrWidth, $this->qrHeight, imagesx($qrCode), imagesy($qrCode));


        //增加微信头像
        if ($this->isWx) {
            if ($this->wxImgSource == 0 && $this->wxImg != '') {
                $wxSource = $this->yuanImg($this->wxImg, $this->wxIsRound, $this->wxWidth, $this->wxHeight);
                @imagecopyresampled($bg, $wxSource, $this->wxPositionX, $this->wxPositionY, 0, 0, $this->wxWidth, $this->wxHeight, @imagesx($wxSource), @imagesy($wxSource));
            } else {
                $wxSource = $this->yuanImgBySource($this->wxImgSource, $this->wxIsRound, $this->sWidth, $this->sHeight, $this->wxWidth, $this->wxHeight);
                @imagecopyresampled($bg, $wxSource, $this->wxPositionX, $this->wxPositionY, 0, 0, $this->wxWidth, $this->wxHeight, @imagesx($wxSource), @imagesy($wxSource));
            }
        }
        //合成logo
        if($this->logoWidth>0) {
            $logoSrc = null;
            $logoImgExt = exif_imagetype(public_path($this->logoSrc));
            switch ($logoImgExt) {
                case 1:
                    $logoSrc = imagecreatefromgif(public_path($this->logoSrc));
                    break;
                case 2:
                    $logoSrc = imagecreatefromjpeg(public_path($this->logoSrc));
                    break;
                case 6:
                    $logoSrc = imagecreatefromwbmp(public_path($this->logoSrc));
                    break;
                case 3:
                    $logoSrc = imagecreatefrompng(public_path($this->logoSrc));
                    break;
            }
            @imagecopyresampled($bg, $logoSrc, $this->logoPositionX, $this->logoPositionY, 0, 0, $this->logoWidth, $this->logoWidth, @imagesx($logoSrc), @imagesy($logoSrc));
        }

        //增加文字
        if ($this->textLineNumber == 1) {
            $one = mb_convert_encoding($this->textOne . '  ' . $this->textTwo, 'html-entities', 'UTF-8');
            $two = '';
        } else {
            $one = mb_convert_encoding($this->textOne, 'html-entities', 'UTF-8');
            $two = mb_convert_encoding($this->textTwo, 'html-entities', 'UTF-8');
        }

        if ($this->textContentType == 1) {
            $one = mb_convert_encoding($this->textOne, 'html-entities', 'UTF-8');
            $two = '';
        }

        $fontBox = imagettfbbox($this->fontSize, 0, $this->fontFile, $one);//文字水平居中实质

        $fontColor = imagecolorallocatealpha($bg, $this->font_color_r, $this->font_color_g, $this->font_color_b, intval((1 - $this->font_color_a) * 127));
        $width = imagesx($bg);
        if ($this->fontPositionX == 0) {
            imagettftext($bg, $this->fontSize, 0, ceil(($width - $fontBox[2]) / 2), $this->fontPositionY, $fontColor, $this->fontFile, $one);
            //如果需要加粗,让x坐标加1
            imagettftext($bg, $this->fontSize, 0, ceil(($width - $fontBox[2]) / 2) + 1, $this->fontPositionY, $fontColor, $this->fontFile, $one);
        } else {
            imagettftext($bg, $this->fontSize, 0, $this->fontPositionX, $this->fontPositionY, $fontColor, $this->fontFile, $one);
            //如果需要加粗,让x坐标加1
            imagettftext($bg, $this->fontSize, 0, $this->fontPositionX + 1, $this->fontPositionY, $fontColor, $this->fontFile, $one);
        }
        if ($two != '') {
            $fontBox = imagettfbbox($this->fontSize, 0, $this->fontFile, $two);//文字水平居中实质
            if ($this->fontPositionX == 0) {
                imagettftext($bg, $this->fontSize, 0, ceil(($width - $fontBox[2]) / 2), $this->fontPositionY + $this->fontNextLineDistance, $fontColor, $this->fontFile, $two);
                //如果需要加粗,让x坐标加1
                imagettftext($bg, $this->fontSize, 0, ceil(($width - $fontBox[2]) / 2) + 1, $this->fontPositionY + $this->fontNextLineDistance, $fontColor, $this->fontFile, $two);
            } else {
                imagettftext($bg, $this->fontSize, 0, $this->fontPositionX, $this->fontPositionY + $this->fontNextLineDistance, $fontColor, $this->fontFile, $two);
                //如果需要加粗,让x坐标加1
                imagettftext($bg, $this->fontSize, 0, $this->fontPositionX + 1, $this->fontPositionY + $this->fontNextLineDistance, $fontColor, $this->fontFile, $two);
            }
        }
        if ($this->qrImgFileName == '') {
            $spreadQrPreviewName = tempnam(sys_get_temp_dir(), 'spread_qr_preview');
        } else {
            $spreadQrPreviewName = $this->qrImgFileName;
        }
        switch ($fileType) {
            case 1:
                if ($this->qrImgFileName == '') {
                    rename($spreadQrPreviewName, $spreadQrPreviewName .= '.gif');
                } else {
                    $spreadQrPreviewName = $spreadQrPreviewName . '.gif';
                }
                imagegif($bg, public_path($spreadQrPreviewName));
                break;
            case 2:
                if ($this->qrImgFileName == '') {
                    rename($spreadQrPreviewName, $spreadQrPreviewName .= '.jpg');
                } else {
                    $spreadQrPreviewName = $spreadQrPreviewName . '.jpg';
                }
                imagejpeg($bg, public_path($spreadQrPreviewName));
                break;
            case 6:
                if ($this->qrImgFileName == '') {
                    rename($spreadQrPreviewName, $spreadQrPreviewName .= '.jpg');
                } else {
                    $spreadQrPreviewName = $spreadQrPreviewName . '.jpg';
                }
                imagejpeg($bg, public_path($spreadQrPreviewName));
                break;
            case 3:
                if ($this->qrImgFileName == '') {
                    rename($spreadQrPreviewName, $spreadQrPreviewName .= '.png');
                } else {
                    $spreadQrPreviewName = $spreadQrPreviewName . '.png';
                }
                imagepng($bg, public_path($spreadQrPreviewName));
                break;
        }
        if ($this->qrImgFileName == '') {
            ImageService::create()->base64EncodeImage($spreadQrPreviewName);
        } else {
            return '/' . $spreadQrPreviewName;
        }
    }

    private function yuanImg($imgPath, $isRound = 1, $headerImgW = 0, $headerImgH = 0) {
        $src_img = null;
        $extension = exif_imagetype($imgPath);
        switch ($extension) {
            case 1:
                $src_img = imagecreatefromgif($imgPath);
                break;
            case 2:
                $src_img = imagecreatefromjpeg($imgPath);
                break;
            case 3:
                $src_img = imagecreatefrompng($imgPath);
                break;
            case 6:
                $src_img = imagecreatefromwbmp($imgPath);
                break;
        }
        if(!$isRound){
            return $src_img;
        }
        $wh  = getimagesize($imgPath);
        $w   = $headerImgW??$wh[0];
        $h   = $headerImgH??$wh[1];
        $w   = min($w, $h);
        $h   = $w;
        $img = imagecreatetruecolor($w, $h);
        //这一句一定要有
        imagesavealpha($img, true);
        //拾取一个完全透明的颜色,最后一个参数127为全透明
        $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $bg);
        $r   = $w / 2; //圆半径
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = imagecolorat($src_img, $x, $y);
                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    imagesetpixel($img, $x, $y, $rgbColor);
                }
            }
        }
        return $img;
    }

    private function yuanImgBySource($source,$isRound = 1,$sWidth=0,$sHeight=0,$headerImgW = 0,$headerImgH = 0){
        if (!$isRound){
            return $source;
        }
        $w   = $headerImgW??640;
        $h   = $headerImgH??640;
        $sWidth = $sWidth??640;
        $sHeight = $sHeight??640;
        //将图片等比例缩小
        $new=imagecreatetruecolor($w, $h);
        imagecopyresized($new, $source,0, 0,0, 0,$w, $h, $sWidth, $sHeight);

        $img = imagecreatetruecolor($w, $h);
        //这一句一定要有
        imagesavealpha($img, true);
        //拾取一个完全透明的颜色,最后一个参数127为全透明
        $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $bg);
        $r   = $w / 2; //圆半径
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = @imagecolorat($source, $x, $y);
                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    @imagesetpixel($img, $x, $y, $rgbColor);
                }
            }
        }
        return $img;
    }
}
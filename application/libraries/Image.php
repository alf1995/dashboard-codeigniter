<?php

class Image 
{
    private $_image;
    private $_imageType;
    private $_transparent;
    private $_validExtensions = array(
        IMAGETYPE_JPEG => 'image/jpeg',
        IMAGETYPE_GIF => 'image/gif',
        IMAGETYPE_PNG => 'image/png',
    );

    private function _imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct) {
        $cut = imagecreateTRUEcolor($src_w, $src_h);
        imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
        imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
        imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
    }

    private function _setPositionWatermark($width, $height, $position = 'bottom right', $paddingH = 10, $paddingV = 10) {
        switch (strtolower($position)) {
            case 'top left':
                $h = $paddingH;
                $v = $paddingV;
                break;
            case 'top center':
                $h = ($this->getWidth() / 2) - ($width / 2) - $paddingH;
                $v = $paddingV;
                break;
            case 'top right':
                $h = $this->getWidth() - $width - $paddingH;
                $v = $paddingV;
                break;
            case 'middle left':
                $h = $paddingH;
                $v = ($this->getHeight() / 2) - ($height / 2) - $paddingV;
                break;
            case 'middle center':
                $h = ($this->getWidth() / 2) - ($width / 2) - $paddingH;
                $v = ($this->getHeight() / 2) - ($height / 2) - $paddingV;
                break;
            case 'middle right':
                $h = $this->getWidth() - $width - $paddingH;
                $v = ($this->getHeight() / 2) - ($height / 2) - $paddingV;
                break;
            case 'bottom left':
                $h = $paddingH;
                $v = $this->getHeight() - $height - $paddingV;
                break;
            case 'bottom center':
                $h = ($this->getWidth() / 2) - ($width / 2) - $paddingH;
                $v = $this->getHeight() - $height - $paddingV;
                break;
            default:
                $h = $this->getWidth() - $width - $paddingH;
                $v = $this->getHeight() - $height - $paddingV;
        }
        return array('horizontal' => $h, 'vertical' => $v);
    }

    public function ready($fileName = null, $transparent = FALSE) {
        $this->setTransparent($transparent);

        if (!is_null($fileName)) {
            $this->load($fileName);
        }
    }

    public function setTransparent($bool) {
        $this->_transparent = (boolean) $bool;
    }

    public function getImageType() {
        return array_key_exists($this->_imageType, $this->_validExtensions) ? $this->_validExtensions[$this->_imageType] : null;
    }

    public function isValidExtension() {
        return array_key_exists($this->_imageType, $this->_validExtensions) ? TRUE : 'Invalid extension, you can upload only ' . implode(', ', $this->_validExtensions);
    }

    public function load($fileName) {
        $imageInfo = getimagesize($fileName);
        $this->_imageType = $imageInfo[2];

        if ($this->_imageType == IMAGETYPE_JPEG) {
            $this->_image = imagecreatefromjpeg($fileName);
        } elseif ($this->_imageType == IMAGETYPE_GIF) {
            $this->_image = imagecreatefromgif($fileName);
        } elseif ($this->_imageType == IMAGETYPE_PNG) {
            $this->_image = imagecreatefrompng($fileName);
        }
    }

    public function save($fileName, $compression = 75, $permissions = null) {
        if ($this->_imageType == IMAGETYPE_JPEG) {
            imagejpeg($this->_image, $fileName, $compression);
        } elseif ($this->_imageType == IMAGETYPE_GIF) {
            imagegif($this->_image, $fileName);
        } elseif ($this->_imageType == IMAGETYPE_PNG) {
            imagepng($this->_image, $fileName);
        }

        if (!is_null($permissions)) {
            chmod($fileName, $permissions);
        }
    }

    public function output() {
        // TODO: Agregar encabezados según el tipo de imagen
        if ($this->_imageType == IMAGETYPE_JPEG) {
            imagejpeg($this->_image);
        } elseif ($this->_imageType == IMAGETYPE_GIF) {
            imagegif($this->_image);
        } elseif ($this->_imageType == IMAGETYPE_PNG) {
            imagepng($this->_image);
        }
    }

    public function getWidth() {
        return imagesx($this->_image);
    }

    public function getHeight() {
        return imagesy($this->_image);
    }

    public function resizeToHeight($height) {
        if ($height < $this->getHeight()) {
            $ratio = $height / $this->getHeight();
            $width = $this->getWidth() * $ratio;
            $this->resize($width, $height);
        } else {
            $this->resize($this->getWidth(), $this->getHeight());
        }
    }

    public function resizeToWidth($width) {
        if ($width < $this->getWidth()) {
            $ratio = $width / $this->getWidth();
            $height = $this->getHeight() * $ratio;
            $this->resize($width, $height);
        } else {
            $this->resize($this->getWidth(), $this->getHeight());
        }
    }

    public function scale($scale) {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getHeight() * $scale / 100;
        $this->resize($width, $height);
    }

    public function resize($width, $height) {
        $newImage = imagecreateTRUEcolor($width, $height);
        if ($this->_imageType == IMAGETYPE_PNG && $this->_transparent === TRUE) {
            imagealphablending($newImage, FALSE);
            imagesavealpha($newImage, TRUE);
            imagefilledrectangle($newImage, 0, 0, $width, $height, imagecolorallocatealpha($newImage, 255, 255, 255, 127));
        } elseif ($this->_imageType == IMAGETYPE_GIF && $this->_transparent === TRUE) {
            $index = imagecolortransparent($this->_image);
            if ($index != -1 && $index != 255) {
                $colors = imagecolorsforindex($this->_image, $index);
                $transparent = imagecolorallocatealpha($newImage, $colors['red'], $colors['green'], $colors['blue'], $colors['alpha']);
                imagefill($newImage, 0, 0, $transparent);
                imagecolortransparent($newImage, $transparent);
            }
        }
        imagecopyresampled($newImage, $this->_image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->_image = $newImage;
    }

    public function resizeToFit($width, $height, $margins = FALSE, $hexBckColor = '000000') {
        $ratioW = $width / $this->getWidth();
        $ratioH = $height / $this->getHeight();
        $ratio = ($margins === FALSE) ? max($ratioW, $ratioH) : min($ratioW, $ratioH);
        $newW = floor($this->getWidth() * $ratio);
        $newH = floor($this->getHeight() * $ratio);

        $this->resize($newW, $newH);

        if ($newW != $width || $newH != $height) {
            $newImage = imagecreateTRUEcolor($width, $height);
            imagefill($newImage, 0, 0, "0x$hexBckColor");

            $ox = ($newW > $width) ? floor(($newW - $width) / 2) : 0;
            $oy = ($newH > $height) ? floor(($newH - $width) / 2) : 0;
            $dx = ($newW < $width) ? floor(($width - $newW) / 2) : 0;
            $dy = ($newH < $height) ? floor(($height - $newH) / 2) : 0;

            imagecopy($newImage, $this->_image, $dx, $dy, $ox, $oy, $newW, $newH);
            $this->_image = $newImage;
        }
    }

    public function imgWatermark($img, $opacity = 100, $position = 'bottom right', $paddingH = 10, $paddingV = 10) {
        $iw = getimagesize($img);
        $width = $iw[0];
        $height = $iw[1];

        $p = $this->_setPositionWatermark($width, $height, $position, $paddingH, $paddingV);

        imagealphablending($this->_image, TRUE);
        $watermark = imagecreatefrompng($img);
        $this->_imagecopymerge_alpha($this->_image, $watermark, $p['horizontal'], $p['vertical'], 0, 0, $width, $height, $opacity);
        imagedestroy($watermark);

        return $this->_image;
    }

    public function stringWatermark($string, $opacity = 100, $color = '000000', $position = 'bottom right', $paddingH = 10, $paddingV = 10) {
        $width = imagefontwidth(5) * strlen($string);
        $height = imagefontwidth(5) + 10;

        $p = $this->_setPositionWatermark($width, $height, $position, $paddingH, $paddingV);

        $watermark = imagecreateTRUEcolor($width, $height);
        imagealphablending($watermark, FALSE);
        imagesavealpha($watermark, TRUE);
        imagefilledrectangle($watermark, 0, 0, $width, $height, imagecolorallocatealpha($watermark, 255, 255, 255, 127));

        imagestring($watermark, 5, 0, 0, $string, "0x$color");
        $this->_imagecopymerge_alpha($this->_image, $watermark, $p['horizontal'], $p['vertical'], 0, 0, $width, $height, $opacity);

        return $this->_image;
    }
    
    public function onFocused($onfocused = 10){
        for ($i=0; $i<$onfocused; $i++) {
            imagefilter($this->_image, IMG_FILTER_GAUSSIAN_BLUR);
        }
    }

}
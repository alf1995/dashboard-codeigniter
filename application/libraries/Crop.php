<?php

class Crop {
    
    public function ready($image_directory, $image_width, $image_height, 
                $image_color = 'FFFFFF', $image_nocache = '', $image_quality = 100){
        $resource_cropratio = $image_width.':'.$image_height;
        if (!isset($image_directory))
        {
                header('HTTP/1.1 400 Bad Request');
                echo 'Error: no image was specified';
                exit();
        }
        define('MEMORY_TO_ALLOCATE',	'100M');
        define('DEFAULT_QUALITY',		90);
        define('CURRENT_DIR',			dirname(__FILE__));
        define('CACHE_DIR_NAME',		'/imagecache/');
        define('CACHE_DIR',				CURRENT_DIR . CACHE_DIR_NAME);
        define('DOCUMENT_ROOT',			$_SERVER['DOCUMENT_ROOT']);
        $image			= preg_replace('/^(s?f|ht)tps?:\/\/[^\/]+/i', '', (string) $image_directory);
        if ($image[0] != '/' || strpos(dirname($image), ':') || preg_match('/(\.\.|<|>)/', $image))
        {
                header('HTTP/1.1 400 Bad Request');
                echo 'Error: malformed image path. Image paths must begin with \'/\'';
                exit();
        }
        if (!$image)
        {
                header('HTTP/1.1 400 Bad Request');
                echo 'Error: no image was specified';
                exit();
        }
        $docRoot	= preg_replace('/\/$/', '', DOCUMENT_ROOT);

        if (!file_exists($docRoot . $image))
        {
                header('HTTP/1.1 404 Not Found');
                echo 'Error: image does not exist: ' . $docRoot . $image;
                exit();
        }
        $size	= GetImageSize($docRoot . $image);
        $mime	= $size['mime'];
        if (substr($mime, 0, 6) != 'image/')
        {
                header('HTTP/1.1 400 Bad Request');
                echo 'Error: requested file is not an accepted type: ' . $docRoot . $image;
                exit();
        }
        $width			= $size[0];
        $height			= $size[1];
        $maxWidth		= (isset($image_width)) ? (int) $image_width : 0;
        $maxHeight		= (isset($image_height)) ? (int) $image_height : 0;
        if (isset($image_color))
                $color		= preg_replace('/[^0-9a-fA-F]/', '', (string) $image_color);
        else
                $color		= FALSE;
        if (!$maxWidth && $maxHeight)
        {
                $maxWidth	= 99999999999999;
        }
        elseif ($maxWidth && !$maxHeight)
        {
                $maxHeight	= 99999999999999;
        }
        elseif ($color && !$maxWidth && !$maxHeight)
        {
                $maxWidth	= $width;
                $maxHeight	= $height;
        }
        if ((!$maxWidth && !$maxHeight) || (!$color && $maxWidth >= $width && $maxHeight >= $height))
        {
                $data	= file_get_contents($docRoot . '/' . $image);
                $lastModifiedString	= gmdate('D, d M Y H:i:s', filemtime($docRoot . '/' . $image)) . ' GMT';
                $etag				= md5($data);
                $this->doConditionalGet($etag, $lastModifiedString);
                header("Content-type: $mime");
                header('Content-Length: ' . strlen($data));
                echo $data;
                exit();
        }
        $offsetX	= 0;
        $offsetY	= 0;

        if (isset($resource_cropratio))
        {
                $cropRatio		= explode(':', (string) $resource_cropratio);
                if (count($cropRatio) == 2)
                {
                        $ratioComputed		= $width / $height;
                        $cropRatioComputed	= (float) $cropRatio[0] / (float) $cropRatio[1];
                        if ($ratioComputed < $cropRatioComputed)
                        {
                                $origHeight	= $height;
                                $height		= $width / $cropRatioComputed;
                                $offsetY	= ($origHeight - $height) / 2;
                        }
                        else if ($ratioComputed > $cropRatioComputed)
                        {
                                $origWidth	= $width;
                                $width		= $height * $cropRatioComputed;
                                $offsetX	= ($origWidth - $width) / 2;
                        }
                }
        }
        $xRatio		= $maxWidth / $width;
        $yRatio		= $maxHeight / $height;
        if ($xRatio * $height < $maxHeight)
        {
                $tnHeight	= ceil($xRatio * $height);
                $tnWidth	= $maxWidth;
        }
        else
        {
                $tnWidth	= ceil($yRatio * $width);
                $tnHeight	= $maxHeight;
        }
        $quality	= (isset($image_quality)) ? (int) $image_quality : DEFAULT_QUALITY;
        $resizedImageSource		= $tnWidth . 'x' . $tnHeight . 'x' . $quality;
        if ($color)
                $resizedImageSource	.= 'x' . $color;
        if (isset($resource_cropratio))
                $resizedImageSource	.= 'x' . (string) $resource_cropratio;
        $resizedImageSource		.= '-' . $image;
        $resizedImage	= md5($resizedImageSource);
        $resized		= CACHE_DIR . $resizedImage;
        if (($image_nocache == '') && file_exists($resized))
        {
                $imageModified	= filemtime($docRoot . $image);
                $thumbModified	= filemtime($resized);
                if($imageModified < $thumbModified) {
                        $data	= file_get_contents($resized);
                        $lastModifiedString	= gmdate('D, d M Y H:i:s', $thumbModified) . ' GMT';
                        $etag				= md5($data);
                        $this->doConditionalGet($etag, $lastModifiedString);
                        header("Content-type: $mime");
                        header('Content-Length: ' . strlen($data));
                        echo $data;
                        exit();
                }
        }
        ini_set('memory_limit', MEMORY_TO_ALLOCATE);
        $dst	= imagecreatetruecolor($tnWidth, $tnHeight);
        switch ($size['mime'])
        {
                case 'image/gif':
                        $creationFunction	= 'ImageCreateFromGif';
                        $outputFunction		= 'ImagePng';
                        $mime				= 'image/png'; 
                        $doSharpen			= FALSE;
                        $quality			= round(10 - ($quality / 10));
                break;
                case 'image/x-png':
                case 'image/png':
                        $creationFunction	= 'ImageCreateFromPng';
                        $outputFunction		= 'ImagePng';
                        $doSharpen			= FALSE;
                        $quality			= round(10 - ($quality / 10));
                break;
                default:
                        $creationFunction	= 'ImageCreateFromJpeg';
                        $outputFunction	 	= 'ImageJpeg';
                        $doSharpen			= TRUE;
                break;
        }
        $src	= $creationFunction($docRoot . $image);
        if (in_array($size['mime'], array('image/gif', 'image/png')))
        {
                if (!$color)
                {
                        imagealphablending($dst, false);
                        imagesavealpha($dst, true);
                }
                else
                {
                        if ($color[0] == '#')
                                $color = substr($color, 1);
                        $background	= FALSE;
                        if (strlen($color) == 6)
                                $background	= imagecolorallocate($dst, hexdec($color[0].$color[1]), hexdec($color[2].$color[3]), hexdec($color[4].$color[5]));
                        else if (strlen($color) == 3)
                                $background	= imagecolorallocate($dst, hexdec($color[0].$color[0]), hexdec($color[1].$color[1]), hexdec($color[2].$color[2]));
                        if ($background)
                                imagefill($dst, 0, 0, $background);
                }
        }
        ImageCopyResampled($dst, $src, 0, 0, $offsetX, $offsetY, $tnWidth, $tnHeight, $width, $height);

        if ($doSharpen)
        {
                $sharpness	= $this->findSharp($width, $tnWidth);
                $sharpenMatrix	= array(
                        array(-1, -2, -1),
                        array(-2, $sharpness + 12, -2),
                        array(-1, -2, -1)
                );
                $divisor		= $sharpness;
                $offset			= 0;
                imageconvolution($dst, $sharpenMatrix, $divisor, $offset);
        }
        if (!file_exists(CACHE_DIR))
                mkdir(CACHE_DIR, 0755);
        if (!is_readable(CACHE_DIR))
        {
                header('HTTP/1.1 500 Internal Server Error');
                echo 'Error: the cache directory is not readable';
                exit();
        }
        else if (!is_writable(CACHE_DIR))
        {
                header('HTTP/1.1 500 Internal Server Error');
                echo 'Error: the cache directory is not writable';
                exit();
        }
        $outputFunction($dst, $resized, $quality);
        ob_start();
        $outputFunction($dst, null, $quality);
        $data	= ob_get_contents();
        ob_end_clean();
        ImageDestroy($src);
        ImageDestroy($dst);
        $lastModifiedString	= gmdate('D, d M Y H:i:s', filemtime($resized)) . ' GMT';
        $etag				= md5($data);

        $this->doConditionalGet($etag, $lastModifiedString);
        header("Content-type: $mime");
        header('Content-Length: ' . strlen($data));
        echo $data;
    }

    public function findSharp($orig, $final) 
    {
            $final	= $final * (750.0 / $orig);
            $a		= 52;
            $b		= -0.27810650887573124;
            $c		= .00047337278106508946;
            $result = $a + $b * $final + $c * $final * $final;
            return max(round($result), 0);
    }

    public function doConditionalGet($etag, $lastModified)
    {
            header("Last-Modified: $lastModified");
            header("ETag: \"{$etag}\"");

            $if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ?
                    stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) : 
                    false;
            $if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ?
                    stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE']) :
                    false;
            if (!$if_modified_since && !$if_none_match)
                    return;
            if ($if_none_match && $if_none_match != $etag && $if_none_match != '"' . $etag . '"')
                    return;
            if ($if_modified_since && $if_modified_since != $lastModified)
                    return;
            header('HTTP/1.1 304 Not Modified');
            exit();
    }
    
}
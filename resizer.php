<?php
/*
W-PHP Resizer
=====================
File: resizer.php
Author: Ali Candan [Webkolog] <webkolog@gmail.com> 
Homepage: http://webkolog.net
GitHub Repo: https://github.com/webkolog/php-resizer
Last Modified: 2016-04-03
Created Date: 2016-04-03
Compatibility: PHP 5.4+
@version     1.0

Copyright (C) 2015 Ali Candan
Licensed under the MIT license http://mit-license.org

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the “Software”), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
class resize {

	private $image;
	private $width;
	private $height;
	private $imageResized;

	function __construct($fileName) {
		$this->image = $this->openImage($fileName);
		// HATA KONTROLÜ: Resim yüklenemezse işlemi durdur
		if (!$this->image) {
			throw new Exception("Dosya yüklenemedi veya geçersiz format: " . $fileName);
		}
		$this->width = imagesx($this->image);
		$this->height = imagesy($this->image);
	}
	
	private function openImage($file) {
		$extension = strtolower(strrchr($file, '.'));
		switch($extension) {
			case '.jpg':
			case '.jpeg':
			$img = @imagecreatefromjpeg($file);
			break;
			case '.gif':
			$img = @imagecreatefromgif($file);
			break;
			case '.png':
			$img = @imagecreatefrompng($file);
			break;
			default:
			$img = false;
			break;
		}
		return $img;
	}
	
	public function resizeImage($newWidth, $newHeight, $option="auto") {
		// HATA KONTROLÜ: 0 değerlerini engelle
		if ($newWidth <= 0 && $newHeight <= 0) {
			$newWidth = $this->width; 
			$newHeight = $this->height;
		}
		$optionArray = $this->getDimensions($newWidth, $newHeight, $option);
		$optimalWidth = $optionArray['optimalWidth'];
		$optimalHeight = $optionArray['optimalHeight'];
		// PHP 8+ koruması: Genişlik ve yükseklik en az 1 olmalı
		$optimalWidth = max(1, $optimalWidth);
		$optimalHeight = max(1, $optimalHeight);
		$this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
		imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);
		if ($option == 'crop')
			$this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
	}

	private function getDimensions($newWidth, $newHeight, $option) {
		switch ($option) {
			case 'exact':
			$optimalWidth = $newWidth;
			$optimalHeight= $newHeight;
			break;
			case 'portrait':
			$optimalWidth = $this->getSizeByFixedHeight($newHeight);
			$optimalHeight= $newHeight;
			break;
			case 'landscape':
			$optimalWidth = $newWidth;
			$optimalHeight= $this->getSizeByFixedWidth($newWidth);
			break;
			case 'auto':
			$optionArray = $this->getSizeByAuto($newWidth, $newHeight);
			$optimalWidth = $optionArray['optimalWidth'];
			$optimalHeight = $optionArray['optimalHeight'];
			break;
			case 'crop':
			$optionArray = $this->getOptimalCrop($newWidth, $newHeight);
			$optimalWidth = $optionArray['optimalWidth'];
			$optimalHeight = $optionArray['optimalHeight'];
			break;
		}
		return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
	}

	private function getSizeByFixedHeight($newHeight) {
		$ratio = $this->width / $this->height;
		$newWidth = $newHeight * $ratio;
		return $newWidth;
	}

	private function getSizeByFixedWidth($newWidth) {
		$ratio = $this->height / $this->width;
		$newHeight = $newWidth * $ratio;
		return $newHeight;
	}

	private function getSizeByAuto($newWidth, $newHeight) {
		if ($this->height < $this->width) {
			$optimalWidth = $newWidth;
			$optimalHeight= $this->getSizeByFixedWidth($newWidth);
		} elseif ($this->height > $this->width) {
			$optimalWidth = $this->getSizeByFixedHeight($newHeight);
			$optimalHeight= $newHeight;
		} else {
			if ($newHeight < $newWidth) {
				$optimalWidth = $newWidth;
				$optimalHeight= $this->getSizeByFixedWidth($newWidth);
			} else if ($newHeight > $newWidth) {
				$optimalWidth = $this->getSizeByFixedHeight($newHeight);
				$optimalHeight= $newHeight;
			} else {
				$optimalWidth = $newWidth;
				$optimalHeight= $newHeight;
			}
		}
		return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
	}

	private function getOptimalCrop($newWidth, $newHeight) {
		$heightRatio = $this->height / $newHeight;
		$widthRatio = $this->width / $newWidth;
		if ($heightRatio < $widthRatio)
			$optimalRatio = $heightRatio;
		else
			$optimalRatio = $widthRatio;
		$optimalHeight = $this->height / $optimalRatio;
		$optimalWidth = $this->width / $optimalRatio;
		return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
	}

	private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight) {
		$cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
		$cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );
		$crop = $this->imageResized;
		$this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
		imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
	}

	public function saveImage($savePath, $imageQuality="100") {
		$extension = strrchr($savePath, '.');
		$extension = strtolower($extension);
		switch($extension)
		{
			case '.jpg':
			case '.jpeg':
			if (imagetypes() & IMG_JPG) {
				imagejpeg($this->imageResized, $savePath, $imageQuality);
			}
			break;
			case '.gif':
			if (imagetypes() & IMG_GIF) {
				imagegif($this->imageResized, $savePath);
			}
			break;
			case '.png':
			$scaleQuality = round(($imageQuality/100) * 9);
			$invertScaleQuality = 9 - $scaleQuality;
			if (imagetypes() & IMG_PNG) {
				imagepng($this->imageResized, $savePath, $invertScaleQuality);
			}
			break;
			default:
			break;
		}
			imagedestroy($this->imageResized);
	}
}

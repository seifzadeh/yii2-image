<?php

/**
 * base class from http://www.paulund.co.uk/resize-image-class-php
 *
 *
 * Resize Exact Size
 * To resize an image to an exact size you can use the following code. First pass in the image we want to resize in the class constructor, then define the width and height with the scale option of exact. The class will now have the create dimensions to create the new image, now call the function saveImage() and pass in the new file location to the new image.
 * $resize = new ResizeImage('images/Be-Original.jpg');
 * $resize->resizeTo(100, 100, 'exact');
 * $resize->saveImage('images/be-original-exact.jpg');
 *
 *
 * Resize Max Width Size
 * If you choose to set the image to be an exact size then when the image is resized it could lose it's aspect ratio, which means the image could look stretched. But if you know the max width that you want the image to be you can resize the image to a max width, this will keep the aspect ratio of the image.
 * $resize = new ResizeImage('images/Be-Original.jpg');
 * $resize->resizeTo(100, 100, 'maxWidth');
 * $resize->saveImage('images/be-original-maxWidth.jpg');
 *
 * Resize Max Height Size
 * Just as you can select a max width for the image while keeping aspect ratio you can also select a max height while keeping aspect ratio.
 * $resize = new ResizeImage('images/Be-Original.jpg');
 * $resize->resizeTo(100, 100, 'maxHeight');
 * $resize->saveImage('images/be-original-maxHeight.jpg');
 *
 *
 * Resize Auto Size From Given Width And Height
 * You can also allow the code to work out the best way to resize the image, so if the image height is larger than the width then it will resize the image by using the height and keeping aspect ratio. If the image width is larger than the height then the image will be resized using the width and keeping the aspect ratio.
 * $resize = new ResizeImage('images/Be-Original.jpg');
 * $resize->resizeTo(100, 100);
 * $resize->saveImage('images/be-original-default.jpg');
 *
 * Download The Resized Image
 * The default behaviour for this class is to save the image on the server, but you can easily change this to download by passing in a true parameter to the saveImage method.
 * $resize = new ResizeImage('images/Be-Original.jpg');
 * $resize->resizeTo(100, 100, 'exact');
 * $resize->saveImage('images/be-original-exact.jpg', "100", true);
 *
 */

namespace persianyii\image;

/**
 * This is just an example.
 */
class Resize extends \yii\base\Widget {

	private $ext;
	private $image;
	private $newImage;
	private $origWidth;
	private $origHeight;
	private $resizeWidth;
	private $resizeHeight;

	/**
	 * Class constructor requires to send through the image filename
	 *
	 * @param string $filename - Filename of the image you want to resize
	 */
	public function __construct($filename) {
		if (file_exists($filename)) {
			$this->setImage($filename);
		} else {
			throw new Exception('Image ' . $filename . ' can not be found, try another image.');
		}
	}

	/**
	 * Set the image variable by using image create
	 *
	 * @param string $filename - The image filename
	 */
	private function setImage($filename) {
		$size = getimagesize($filename);
		$this->ext = $size['mime'];

		switch ($this->ext) {
			// Image is a JPG
			case 'image/jpg':
			case 'image/jpeg':
				// create a jpeg extension
				$this->image = imagecreatefromjpeg($filename);
				break;

			// Image is a GIF
			case 'image/gif':
				$this->image = @imagecreatefromgif($filename);
				break;

			// Image is a PNG
			case 'image/png':
				$this->image = @imagecreatefrompng($filename);
				break;

			// Mime type not found
			default:
				throw new Exception("File is not an image, please use another file type.", 1);
		}

		$this->origWidth = imagesx($this->image);
		$this->origHeight = imagesy($this->image);
	}

	/**
	 * Save the image as the image type the original image was
	 *
	 * @param  String[type] $savePath     - The path to store the new image
	 * @param  string $imageQuality 	  - The qulaity level of image to create
	 *
	 * @return Saves the image
	 */
	public function saveImage($savePath, $imageQuality = "100", $download = false) {
		switch ($this->ext) {
			case 'image/jpg':
			case 'image/jpeg':
				// Check PHP supports this file type
				if (imagetypes() & IMG_JPG) {
					imagejpeg($this->newImage, $savePath, $imageQuality);
				}
				break;

			case 'image/gif':
				// Check PHP supports this file type
				if (imagetypes() & IMG_GIF) {
					imagegif($this->newImage, $savePath);
				}
				break;

			case 'image/png':
				$invertScaleQuality = 9 - round(($imageQuality / 100) * 9);

				// Check PHP supports this file type
				if (imagetypes() & IMG_PNG) {
					imagepng($this->newImage, $savePath, $invertScaleQuality);
				}
				break;
		}

		if ($download) {
			header('Content-Description: File Transfer');
			header("Content-type: application/octet-stream");
			header("Content-disposition: attachment; filename= " . $savePath . "");
			readfile($savePath);
		}

		imagedestroy($this->newImage);
	}

	/**
	 * Resize the image to these set dimensions
	 *
	 * @param  int $width        	- Max width of the image
	 * @param  int $height       	- Max height of the image
	 * @param  string $resizeOption - Scale option for the image
	 *
	 * @return Save new image
	 */
	public function resizeTo($width, $height, $resizeOption = 'default') {
		switch (strtolower($resizeOption)) {
			case 'exact':
				$this->resizeWidth = $width;
				$this->resizeHeight = $height;
				break;

			case 'maxwidth':
				$this->resizeWidth = $width;
				$this->resizeHeight = $this->resizeHeightByWidth($width);
				break;

			case 'maxheight':
				$this->resizeWidth = $this->resizeWidthByHeight($height);
				$this->resizeHeight = $height;
				break;

			default:
				if ($this->origWidth > $width || $this->origHeight > $height) {
					if ($this->origWidth > $this->origHeight) {
						$this->resizeHeight = $this->resizeHeightByWidth($width);
						$this->resizeWidth = $width;
					} else if ($this->origWidth < $this->origHeight) {
						$this->resizeWidth = $this->resizeWidthByHeight($height);
						$this->resizeHeight = $height;
					} else {
						$this->resizeWidth = $width;
						$this->resizeHeight = $height;
					}
				} else {
					$this->resizeWidth = $width;
					$this->resizeHeight = $height;
				}
				break;
		}

		$this->newImage = imagecreatetruecolor($this->resizeWidth, $this->resizeHeight);
		imagecopyresampled($this->newImage, $this->image, 0, 0, 0, 0, $this->resizeWidth, $this->resizeHeight, $this->origWidth, $this->origHeight);
	}

	/**
	 * Get the resized height from the width keeping the aspect ratio
	 *
	 * @param  int $width - Max image width
	 *
	 * @return Height keeping aspect ratio
	 */
	private function resizeHeightByWidth($width) {
		return floor(($this->origHeight / $this->origWidth) * $width);
	}

	/**
	 * Get the resized width from the height keeping the aspect ratio
	 *
	 * @param  int $height - Max image height
	 *
	 * @return Width keeping aspect ratio
	 */
	private function resizeWidthByHeight($height) {
		return floor(($this->origWidth / $this->origHeight) * $height);
	}
}

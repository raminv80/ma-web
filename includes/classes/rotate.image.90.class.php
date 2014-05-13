<?php
/*
 --------------------------------------------------------------------------------------------
Credits: Bit Repository

Source URL: http://www.bitrepository.com/web-programming/php/crop-rectangle-to-square.html
--------------------------------------------------------------------------------------------
*/

/* Crop Image Class */

class Rotate_Image_90 {

	var $source_image;
	var $new_image_name;
	var $save_to_folder;
	var $save_to_folderm;

	function rotate($degrees = 90)
	{
		$info = GetImageSize($this->source_image);
		$width = $info[0];
		$height = $info[1];
		$mime = $info['mime'];
		
		$image = $this->imagecreatefromfile(trim($this->source_image,"/"));
		
		$width = imagesx($image);
		$height = imagesy($image);

		// What sort of image?
		$type = substr(strrchr($mime, '/'), 1);
		switch ($type)
		{
			case 'jpeg':
				$image_create_func = 'ImageCreateFromJPEG';
				$image_save_func = 'ImageJPEG';
				$new_image_ext = 'jpg';
				break;

			case 'png':
				$image_create_func = 'ImageCreateFromPNG';
				$image_save_func = 'ImagePNG';
				$new_image_ext = 'png';
				break;

			case 'bmp':
				$image_create_func = 'ImageCreateFromBMP';
				$image_save_func = 'ImageBMP';
				$new_image_ext = 'bmp';
				break;

			case 'gif':
				$image_create_func = 'ImageCreateFromGIF';
				$image_save_func = 'ImageGIF';
				$new_image_ext = 'gif';
				break;

			case 'vnd.wap.wbmp':
				$image_create_func = 'ImageCreateFromWBMP';
				$image_save_func = 'ImageWBMP';
				$new_image_ext = 'bmp';
				break;

			case 'xbm':
				$image_create_func = 'ImageCreateFromXBM';
				$image_save_func = 'ImageXBM';
				$new_image_ext = 'xbm';
				break;

			default:
				$image_create_func = 'ImageCreateFromJPEG';
				$image_save_func = 'ImageJPEG';
				$new_image_ext = 'jpg';
		}

		// Rotate
		$rotate = imagerotate($image, $degrees, 0);

		$save_path = $this->source_image;

		// Save image
		$process = $image_save_func($rotate, $save_path) or die("There was a problem in saving the new file.");

		return array('result' => $process, 'new_file_path' => $save_path);
	}

	function new_image_name($filename)
	{
		$string = trim($filename);
		$string = strtolower($string);
		$string = trim(ereg_replace("[^ A-Za-z0-9_]", " ", $string));
		$string = ereg_replace("[ \t\n\r]+", "_", $string);

		$string = str_replace(" ", '_', $string);
		$string = ereg_replace("[ _]+", "_", $string);

		return $string;
	}
	
	/**
	 * loads a png, jpeg or gif image from the given file name
	 */ 
	function imagecreatefromfile($image_path) {
		// retrieve the type of the provided image file
		list($width, $height, $image_type) = getimagesize($image_path);
	
		// select the appropriate imagecreatefrom* function based on the determined
		// image type
		switch ($image_type)
		{
			case IMAGETYPE_GIF: return imagecreatefromgif($image_path); break;
			case IMAGETYPE_JPEG: return imagecreatefromjpeg($image_path); break;
			case IMAGETYPE_PNG: return imagecreatefrompng($image_path); break;
			default: return ''; break;
		}
	}
	
	/**
	 * Function takes an image uri, an image watermark(png) uri, top and left reference positions as a % of 100, to place the watermark.
	 * @param Image URI $_img
	 * @param Watermark URI $_wm
	 * @param Width in pixels $_width
	 * @param Height in pixels $_height
	 */
	function resize($_img, $_width, $_height){
		// load source image to memory
		// load source image to memory
		$image = $this->imagecreatefromfile(trim($_img,"/"));
		if (!$image) die('Unable to open image');
	
		$maxwidth = intval($_width);
		$maxheight = intval($_height);
		if ($maxwidth == 0 && $maxheight == 0) die('Unable to new scale');
	
		$scalex = $maxwidth / imagesx($image); //original scale image size //If the TAG size changes this will need to be scaled.
		$scaley = $maxheight / imagesy($image); //original scale image size //If the TAG size changes this will need to be scaled.
		if(($scalex < $scaley && $maxwidth != 0) || $maxheight == 0){
			$width = imagesx($image);
			$height = imagesy($image);
			$nWidth = $width * $scalex;
			$nHeight = $height * $scalex;
			$image = $this->resizeimage($image, $width, $height, $nWidth, $nHeight);
		}else{
			$width = imagesx($image);
			$height = imagesy($image);
			$nWidth = $width * $scaley;
			$nHeight = $height * $scaley;
			$image = $this->resizeimage($image, $width, $height, $nWidth, $nHeight);
		}
	
		return $image;
	}
	
	function resizeimage($image, $width, $height, $nWidth, $nHeight){
		$newImg = imagecreatetruecolor($nWidth, $nHeight);
		imagealphablending($newImg, false);
		imagesavealpha($newImg,true);
		$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
		imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
		imagecopyresampled($newImg, $image, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
		return $newImg;
	}
}
?>
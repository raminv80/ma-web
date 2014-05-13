<?php
/*
 --------------------------------------------------------------------------------------------
Credits: Bit Repository

Source URL: http://www.bitrepository.com/web-programming/php/crop-rectangle-to-square.html
--------------------------------------------------------------------------------------------
*/

/* Crop Image Class */

class Crop_Image_To_Square {

	var $source_image;
	var $new_image_name;
	var $save_to_folder;
	var $save_to_folderm;

	function crop($location = 'center')
	{
		$info = GetImageSize($this->source_image);
		$width = $info[0];
		$height = $info[1];
		$mime = $info['mime'];
		
		$image = $this->resize($this->source_image,'0','500');
		
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

		// Coordinates calculator

		if($width > $height) // Horizontal Rectangle?
		{
		   if($location == 'center')
		   {
		   	$x_pos = ($width - $height) / 2;
		   	$x_pos = ceil($x_pos);
	
		   	$y_pos = 0;
		   }
		   else if($location == 'left')
		   {
		   	$x_pos = 0;
		   	$y_pos = 0;
		   }
		   else if($location == 'right')
		   {
		   	$x_pos = ($width - $height);
		   	$y_pos = 0;
		   }
	
		   $new_width = $height;
		   $new_height = $height;
			}
			else if($height > $width) // Vertical Rectangle?
			{
		   if($location == 'center')
		   {
		   	$x_pos = 0;
	
		   	$y_pos = ($height - $width) / 2;
		   	$y_pos = ceil($y_pos);
		   }
		   else if($location == 'left')
		   {
		   	$x_pos = 0;
		   	$y_pos = 0;
		   }
		   else if($location == 'right')
		   {
		   	$x_pos = 0;
		   	$y_pos = ($height - $width);
		   }
	
		   $new_width = $width;
		   $new_height = $width;
		}else{
			$new_width = $width;
			$new_height = $height;
			$x_pos = 0;
			$y_pos = 0;
		}

		//$image = $image_create_func($this->source_image);

		$new_image = ImageCreateTrueColor($new_width, $new_height);

		// Crop to Square using the given dimensions
		ImageCopy($new_image, $image, 0, 0, $x_pos, $y_pos, $width, $height);

		if($this->save_to_folder)
		{
			if($this->new_image_name)
			{
				$new_name = $this->new_image_name.'.'.$new_image_ext;
			}
			else
			{
				//$new_name = $this->new_image_name( basename($this->source_image) ).'_square_'.$location.'.'.$new_image_ext;
				//$new_name = $this->new_image_name( basename($this->source_image) ).'.'.$new_image_ext;
				$new_name =  basename($this->source_image);
			}
			$save_path = $this->save_to_folder.$new_name;
			if($this->save_to_folderm){
				$save_pathm = $this->save_to_folderm.$new_name;
			}
		}
		else
		{
			/* Show the image (on the fly) without saving it to a folder */
			header("Content-Type: ".$mime);
			$image_save_func($new_image);
			$save_path = '';
		}

		// Save image
		$process = $image_save_func($new_image, $save_path) or die("There was a problem in saving the new file.");
		$process = $image_save_func($image, $save_pathm) or die("There was a problem in saving the new file.");

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
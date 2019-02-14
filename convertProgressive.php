<?php
ini_set('display_errors',0);
//ini_set('error_reporting', E_ALL); 
/*  PROJECT INFO --------------------------------------------------------------------------------------------------------
    Project:   Convert Images to Progressive JEPG,
    Version:   0.5
/* CONFIG ----------------------------------------------------------------------------------------------------------- */

////////////////////
// REQUEST HANDLER
////////////////////
ini_set('session.gc_maxlifetime', 7200);
session_set_cookie_params(7200);
session_start();

/* get all of the required data from the HTTP request */
$jpg_quality   = (!empty($_REQUEST['quality']) && intval($_REQUEST['quality']) > 0)?intval($_REQUEST['quality']*10):90; // the quality of any generated JPGs on a scale of 0 to 100
$quality = (!empty($_REQUEST['quality']) && intval($_REQUEST['quality']) > 0)?intval(ceil($_REQUEST['quality'])):9; //0 - 9 (0= no compression, 9 = high compression)
$width = ((!empty($_REQUEST['width']) && intval($_REQUEST['width']) > 0)?intval($_REQUEST['width']):null);
$height = ((!empty($_REQUEST['height']) && intval($_REQUEST['height']) > 0)?intval($_REQUEST['height']):null);
$sharpen       = TRUE; // Shrinking images can blur details, perform a sharpen on re-scaled images?
$browser_cache = 60*60*24*7; // How long the BROWSER cache should last (seconds, minutes, hours, days. 7days by default)
$optim            = "/optimised/";
$document_root    = rtrim($_SERVER['DOCUMENT_ROOT'],'/');
$requested_uri    = parse_url(urldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH); //URL
$requested_file   = urlencode(basename($requested_uri));
$source_file      = $document_root.$requested_uri; // Full path to source file
$optim_source_file      = $document_root.$optim.$requested_uri; // Full path to optimised source file
$watermark = "";//$document_root.'images/watermark.png';
$crop = (!empty($_REQUEST['crop']) && !empty($width) && !empty($height))?true:false;

//CHECK FILE EXISTS
if(!file_exists($source_file)){
 // header("HTTP/1.0 404 Not Found"); //PHP ErrorDocument
 // header("Status: 404 Not Found"); //FastCGI

  $requested_uri = '/images/no-image-available.png';
  $source_file = $document_root.$requested_uri;
  $optim_source_file = $document_root.$optim.$requested_uri; 
  if(!file_exists($source_file)){
    $im = imagecreate(160, 120);
    $bg = imagecolorallocate($im, 220, 220, 220);
    $textcolor = imagecolorallocate($im, 0, 0, 0);
    imagestring($im, 4, 8, 40, 'NO IMAGE AVAILABLE', $textcolor);
    ImageInterlace($im, true); 
    ImagePng($im, $source_file, 9);
    imagedestroy($im);
  }
}


if(!empty($width) || !empty($height) || (!empty($_REQUEST['quality']) && intval($_REQUEST['quality']) > 0)){
  $requested_directory  = urlencode(dirname($requested_uri));
  $_f_arr = explode('.', $requested_file,2);
  //$requested_file = $_f_arr[0].((!empty($_REQUEST['width']) && intval($_REQUEST['width']) > 0)?"w".intval($_REQUEST['width']):"").((!empty($_REQUEST['height']) && intval($_REQUEST['height']) > 0)?"h".intval($_REQUEST['height']):"").'.'.$_f_arr[1];
  $requested_file = $_f_arr[0]
  .(!empty($width)?"w".$width:"")
  .(!empty($height)?"h".$height:"")
  .((!empty($height)&&!empty($width)&&!empty($_REQUEST['crop']))?"c":"")
  .(!empty($quality)?"q".$quality:"")
  .'.'.$_f_arr[1];
  $optim_source_file      = $document_root.$optim.parse_url(urldecode($requested_directory)."/".($requested_file),PHP_URL_PATH);
}

/* It exists as a source file, and it doesn't exist cached - lets make one: */
if(!file_exists($optim_source_file) || filemtime($source_file) > filemtime($optim_source_file) || (!empty($watermark) && filemtime($watermark) > filemtime($optim_source_file) ) ){
  
  //BUILD OPTIMISED IMAGE
  $file = generateImage($source_file, $optim_source_file,$width, $height, $crop);
  
  // ADD WATERMARK TO FILES IN 'UPLOADS'
  if($watermark){
    $directory = str_replace($document_root.$optim, '', $optim_source_file);
    if(preg_match('/^\/uploads\//', $directory)){ 
    	addWatermark($optim_source_file, $watermark);
    }
  }

}

sendImage($optim_source_file, $browser_cache);


/* helper function: Send headers and returns an image. */
function sendImage($filename, $browser_cache) {
  $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
  if (in_array($extension, array('png', 'gif', 'jpeg'))) {
    header("Content-Type: image/".$extension);
  } else {
    header("Content-Type: image/jpeg");
  }
  
  $directory = str_replace($_SERVER['DOCUMENT_ROOT']."optimised/", '', $filename);
  if(preg_match('/^\/uploads\/entry_|^\/images\//', $directory)){
  	header("Cache-Control: private, max-age=".$browser_cache);
  	header('Expires: '.gmdate('D, d M Y H:i:s', time()+$browser_cache).' GMT');
  }else{
  	header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
  	header('Pragma: no-cache'); // HTTP 1.0.
  	header('Expires: 0'); // Proxies.
  }
  header('Content-Length: '.filesize($filename));
  readfile($filename);
  exit();
}

/* generates the given cache file for the given source file with the given resolution */
function generateImage($source_file, $cache_file,$_width,$_height, $_crop = false) {
	ini_set('memory_limit','512M');
	global $sharpen, $jpg_quality,$quality;

	make_path($cache_file);

	$extension = strtolower(pathinfo($source_file, PATHINFO_EXTENSION));
	
	switch ($extension) {
		case 'png':
			$src = @ImageCreateFromPng($source_file); // original image
			break;
		case 'gif':
			$src = @ImageCreateFromGif($source_file); // original image
			break;
		default:
			$src = @ImageCreateFromJpeg($source_file); // original image
			break;
	}
	if(!$src){
		$src = imagecreatefromstring(file_get_contents($source_file));
	}
	
	$maxwidth = intval($_width);
	$maxheight = intval($_height);
	$width = imagesx($src);
	$height = imagesy($src);
	$scalex = $maxwidth / imagesx($src); //original scale image size //If the TAG size changes this will need to be scaled.
	$scaley = $maxheight / imagesy($src); //original scale image size //If the TAG size changes this will need to be scaled.
	
	if($_crop && $maxwidth > 0 && $maxheight > 0){
	    if(($scaley < $scalex && $maxwidth != 0) || $maxheight == 0){
	      $nWidth = $width * $scalex;
	      $nHeight = $height * $scalex;
	      $src = resizeimage($src, $width, $height, $nWidth, $nHeight);
	      $src = cropimage($src, $maxwidth, $maxheight);
	    }else{
	      $nWidth = $width * $scaley;
	      $nHeight = $height * $scaley;
	      $src = resizeimage($src, $width, $height, $nWidth, $nHeight);
	      $src = cropimage($src, $maxwidth, $maxheight);
	    }
	}else{
	  if(($maxwidth > 0 || $maxheight > 0)){
	    if(($scalex < $scaley && $maxwidth != 0) || $maxheight == 0){
	      $nWidth = $width * $scalex;
	      $nHeight = $height * $scalex;
	      $src = resizeimage($src, $width, $height, $nWidth, $nHeight);
	    }else{
	      $nWidth = $width * $scaley;
	      $nHeight = $height * $scaley;
	      $src = resizeimage($src, $width, $height, $nWidth, $nHeight);
	    }
	  }
	}
  
  
	// save the new file in the appropriate path, and send a version to the browser
	switch ($extension) {
		case 'png':
			ImageInterlace($src, true); // Enable interlancing (progressive JPG, smaller size file)
			imagealphablending($src, false);
			imagesavealpha($src,true);
			$transparent = imagecolorallocatealpha($src, 255, 255, 255, 127);
			$gotSaved = ImagePng($src, $cache_file, $quality);
			break;
		case 'gif':
			ImageInterlace($src, true); // Enable interlancing (progressive JPG, smaller size file)
			$gotSaved = ImageGif($src, $cache_file);
			break;
		default:
			ImageInterlace($src, true); // Enable interlancing (progressive JPG, smaller size file)
			$gotSaved = ImageJpeg($src, $cache_file, $jpg_quality);
			break;
	}
	ImageDestroy($src);

	return $cache_file;
}


/**
 Make a nested path , creating directories down the path
 Recursion !!
 */
function make_path($path) {
  $dir = pathinfo($path,PATHINFO_DIRNAME);
  
  if(is_dir($dir)){
    return true;
  }else{
    if(make_path($dir)){
      if(mkdir($dir)){
        chmod($dir,0777);
        return true;
      }
    }
  }
  
  return false;
}

function resizeimage($image, $width, $height, $nWidth, $nHeight){
	ini_set('memory_limit','128M');
	$newImg = imagecreatetruecolor($nWidth, $nHeight);
	imagecolortransparent($newImg, imagecolorallocatealpha($newImg, 255, 255, 255, 127));
	imagealphablending($newImg, false);
	imagesavealpha($newImg,true);
	imagecopyresampled($newImg, $image, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
	imagedestroy($image);
	return $newImg;
}

function cropimage($image, $width, $height, $focus="center"){
	ini_set('memory_limit','128M');
	// Coordinates calculator
	//   if($focus == 'center')
	//   {
	$x_pos = (imagesx($image) - $width) / 2;
	$x_pos = ceil($x_pos);
	$y_pos = (imagesy($image) - $height) / 2;
	$y_pos = ceil($y_pos);
	//   }
	
	$newImg = ImageCreateTrueColor($width, $height);
	imagecolortransparent($newImg, imagecolorallocatealpha($newImg, 255, 255, 255, 127));
	imagealphablending($newImg, false);
	imagesavealpha($newImg,true);

	// Crop to Square using the given dimensions
	imagecopy($newImg, $image, 0, 0, $x_pos, $y_pos, $width, $height);
	imagedestroy($image);
	return $newImg;
}


function addWatermark($_image, $_watermark){
	ini_set('memory_limit','128M');
	global $sharpen, $jpg_quality,$quality;
	
	$watermark = @ImageCreateFromPng($_watermark);
	if (!$watermark) die('Unable to open watermark');
	
	$extension = strtolower(pathinfo($_image, PATHINFO_EXTENSION));
	switch ($extension) {
		case 'png':
			$image = @ImageCreateFromPng($_image); // original image
			break;
		case 'gif':
			$image = @ImageCreateFromGif($_image); // original image
			break;
		default:
			$image = @ImageCreateFromJpeg($_image); // original image
			break;
	}
	if(!$image){
	  $image = imagecreatefromstring(file_get_contents($_image));
	}
	
	$scalex = imagesx($image) * 0.11 / 350 ; //original scale image size //If the TAG size changes this will need to be scaled.
	$scaley = imagesy($image) * 0.11 / 121; //original scale image size //If the TAG size changes this will need to be scaled.
	if($scalex > $scaley){
		$width = imagesx($watermark);
		$height = imagesy($watermark);
		$nWidth = $width * $scalex;
		$nHeight = $height * $scalex;
		$watermark = resizeimage($watermark, $width, $height, $nWidth, $nHeight);
	}else{
		$width = imagesx($watermark);
		$height = imagesy($watermark);
		$nWidth = $width * $scaley;
		$nHeight = $height * $scaley;
		$watermark = resizeimage($watermark, $width, $height, $nWidth, $nHeight);
	}
	
	// calculate the position of the watermark in the output image (the
	// watermark shall be placed in the lower right corner)
	$watermark_pos_x = imagesx($image) - imagesx($watermark) - 8;
	$watermark_pos_y = imagesy($image) - imagesy($watermark) - 10;

	
	// merge the source image and the watermark
	imagecopy($image, $watermark,  $watermark_pos_x, $watermark_pos_y, 0, 0, imagesx($watermark), imagesy($watermark));
	
	//$response = imagejpeg($image, $_image, 100);  // use best image quality (100)
	// save the new file in the appropriate path, and send a version to the browser
	switch ($extension) {
	  case 'png':
	    ImageInterlace($image, true); // Enable interlancing (progressive JPG, smaller size file)
	    imagealphablending($image, false);
	    imagesavealpha($image,true);
	    $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
	    $response = ImagePng($image, $_image, $quality);
	    break;
	  case 'gif':
	    ImageInterlace($image, true); // Enable interlancing (progressive JPG, smaller size file)
	    $response = ImageGif($image, $_image);
	    break;
	  default:
	    ImageInterlace($image, true); // Enable interlancing (progressive JPG, smaller size file)
	    $response = ImageJpeg($image, $_image, $jpg_quality);
	    break;
	}
	
	// remove the images from memory
	imagedestroy($image);
	imagedestroy($watermark);
	
	return $response;
}
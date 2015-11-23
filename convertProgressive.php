<?php
ini_set('display_errors',1);
ini_set('error_reporting', E_ALL);
/*  PROJECT INFO --------------------------------------------------------------------------------------------------------
    Project:   Convert Images to Progressive JEPG,
    Version:   0.5
/* CONFIG ----------------------------------------------------------------------------------------------------------- */

////////////////////
// REQUEST HANDLER
////////////////////
session_start();

/* get all of the required data from the HTTP request */
$jpg_quality   = 75; // the quality of any generated JPGs on a scale of 0 to 100
$quality = 9; //0 - 9 (0= no compression, 9 = high compression)
$sharpen       = TRUE; // Shrinking images can blur details, perform a sharpen on re-scaled images?
$browser_cache = 60*60*24*7; // How long the BROWSER cache should last (seconds, minutes, hours, days. 7days by default)
$optim            = "optimised/";
$document_root    = $_SERVER['DOCUMENT_ROOT'];
$requested_uri    = parse_url(urldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH); //URL
$requested_file   = urlencode(basename($requested_uri));
$source_file      = $document_root.$requested_uri; // Full path to source file
$extension = strtolower(pathinfo($source_file, PATHINFO_EXTENSION));
// $optim_source_file      = $document_root.$optim.str_replace($extension, "jpg", $requested_file); // Full path to optimised source file
$optim_source_file      = $document_root.$optim.$requested_uri; // Full path to optimised source file

//CHECK FILE EXISTS
if(!file_exists($source_file)){
  header("HTTP/1.0 404 Not Found"); //PHP ErrorDocument
  header("Status: 404 Not Found"); //FastCGI
  //header("location: /404");
  //die();
  $requested_uri = "images/no-image-available.jpg";
  $requested_file   = urlencode(basename($requested_uri));
  $source_file      = $document_root.$requested_uri; // Full path to source file
  $extension = strtolower(pathinfo($source_file, PATHINFO_EXTENSION));
  $optim_source_file      = $document_root.$optim.$requested_uri;
  if( empty($_REQUEST['width']) || empty($_REQUEST['height']) ) {
    $_REQUEST['width'] = 60;
    $_REQUEST['height'] = 60;
  }
}

if((!empty($_REQUEST['width']) && intval($_REQUEST['width']) > 0) || (!empty($_REQUEST['height']) && intval($_REQUEST['height']) > 0)){
  $requested_directory  = urlencode(dirname($requested_uri));
  $_f_arr = explode('.', $requested_file,2);
  $requested_file = $_f_arr[0].((!empty($_REQUEST['width']) && intval($_REQUEST['width']) > 0)?"w".intval($_REQUEST['width']):"").((!empty($_REQUEST['height']) && intval($_REQUEST['height']) > 0)?"h".intval($_REQUEST['height']):"").'.'.$_f_arr[1];
  //$requested_file       = str_replace(".".$extension, ((!empty($_REQUEST['width']) && intval($_REQUEST['width']) > 0)?"w".intval($_REQUEST['width']):"").((!empty($_REQUEST['height']) && intval($_REQUEST['height']) > 0)?"h".intval($_REQUEST['height']):"").".".$extension, $requested_file);
  $optim_source_file      = $document_root.$optim.parse_url(urldecode($requested_directory)."/".($requested_file),PHP_URL_PATH);
}

if(!file_exists($optim_source_file) || filemtime($source_file) > filemtime($optim_source_file)){
  //BUILD OPTIMISED IMAGE
  /* It exists as a source file, and it doesn't exist cached - lets make one: */
  $width = ((!empty($_REQUEST['width']) && intval($_REQUEST['width']) > 0)?intval($_REQUEST['width']):null);
  $height = ((!empty($_REQUEST['height']) && intval($_REQUEST['height']) > 0)?intval($_REQUEST['height']):null);
  $crop = (!empty($_REQUEST['crop'])?true:false);
  $file = generateImage($source_file, $optim_source_file,$width, $height, $crop);
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
  header("Cache-Control: private, max-age=".$browser_cache);
  header('Expires: '.gmdate('D, d M Y H:i:s', time()+$browser_cache).' GMT');
  header('Content-Length: '.filesize($filename));
  readfile($filename);
  exit();
}

/* generates the given cache file for the given source file with the given resolution */
function generateImage($source_file, $cache_file,$_width,$_height,$_crop=false) {
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
	
	$maxwidth = intval($_width);
	$maxheight = intval($_height);
	if($_crop && $maxwidth > 0 && $maxheight > 0){
    if(($maxwidth > 0 || $maxheight > 0)){
      $scalex = $maxwidth / imagesx($src); //original scale image size //If the TAG size changes this will need to be scaled.
      $scaley = $maxheight / imagesy($src); //original scale image size //If the TAG size changes this will need to be scaled.
      if(($scaley < $scalex && $maxwidth != 0) || $maxheight == 0){
        $width = imagesx($src);
        $height = imagesy($src);
        $nWidth = $width * $scalex;
        $nHeight = $height * $scalex;
        $src = resizeimage($src, $width, $height, $nWidth, $nHeight);
        $src = cropimage($src, $maxwidth, $maxheight);
      }else{
        $width = imagesx($src);
        $height = imagesy($src);
        $nWidth = $width * $scaley;
        $nHeight = $height * $scaley;
        $src = resizeimage($src, $width, $height, $nWidth, $nHeight);
        $src = cropimage($src, $maxwidth, $maxheight);
      }
    }
  }else{
    if(($maxwidth > 0 || $maxheight > 0)){
      $scalex = $maxwidth / imagesx($src); //original scale image size //If the TAG size changes this will need to be scaled.
      $scaley = $maxheight / imagesy($src); //original scale image size //If the TAG size changes this will need to be scaled.
      if(($scalex < $scaley && $maxwidth != 0) || $maxheight == 0){
        $width = imagesx($src);
        $height = imagesy($src);
        $nWidth = $width * $scalex;
        $nHeight = $height * $scalex;
        $src = resizeimage($src, $width, $height, $nWidth, $nHeight);
      }else{
        $width = imagesx($src);
        $height = imagesy($src);
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
	imagealphablending($newImg, false);
	imagesavealpha($newImg,true);
	$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
	imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
	imagecopyresampled($newImg, $image, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
	return $newImg;
}

function cropimage($image,$width, $height, $focus="center"){
	ini_set('memory_limit','128M');
	// Coordinates calculator
	//   if($focus == 'center')
	//   {
	$x_pos = (imagesx($image) - $width) / 2;
	$x_pos = ceil($x_pos);
	$y_pos = (imagesy($image) - $height) / 2;
	$y_pos = ceil($y_pos);
	//   }
	$new_image = ImageCreateTrueColor($width, $height);
	// Crop to Square using the given dimensions
	ImageCopy($new_image, $image, 0, 0, $x_pos, $y_pos, $width, $height);
	return $new_image;
}
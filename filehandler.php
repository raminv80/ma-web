<?php 

$ftypes = array("image","pdf","doc");
$exclude = array(".htaccess",".htpassword");
$paths = array("/uploads","/files","/protected");


////////////////////
// REQUEST HANDLER
////////////////////

//CHECK FILE TYPE
if(empty($_GET['type']) || !in_array($_GET['type'], $ftypes)) {
	header("HTTP/1.0 404 Not Found"); //PHP ErrorDocument
	//header("Status: 404 Not Found"); //FastCGI
	die();
}
//CHECK FILE NAME
if(empty($_GET['file']) || in_array($_GET['file'], $exclude) ){
	header("HTTP/1.0 404 Not Found"); //PHP ErrorDocument
	//header("Status: 404 Not Found"); //FastCGI
	die();
}


//CHECK FILE LOCATIONS





//////////////
// FUNCTIONS
//////////////


// loads a png, jpeg or gif image from the given file name
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

function resizeimage($image, $width, $height, $nWidth, $nHeight){
	$newImg = imagecreatetruecolor($nWidth, $nHeight);
	imagealphablending($newImg, false);
	imagesavealpha($newImg,true);
	$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
	imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
	imagecopyresampled($newImg, $image, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
	return $newImg;
}

/**
 * This function places a watermark image ontop of another image. It scales the watermark before applying it.
 * 
 * Function takes an image uri, an image watermark(png) uri, top and left reference positions as a % of 100, to place the watermark.
 * @param Image URI $_img
 * @param Watermark URI $_wm
 * @param Top reference 0-100 $_tref
 * @param Left reference 0-100 $_lref
 * @param Max X scale 0-1 $_xscale
 * @param Max Y scale 0-1 $_yscale
 */
function waterMark($_img, $_wm, $_tref, $_lref, $_xscale, $_yscale){
	// load source image to memory
	$image = imagecreatefromfile(trim($_img,"/"));
	if (!$image) die('Unable to open image');
	
	// load watermark to memory
	$watermark = imagecreatefromfile(trim($_wm,"/"));
	if (!$image) die('Unable to open watermark');
	
	$xchk = imagesx($image) / $_xscale;
	$ychk = imagesy($image) / $_yscale;
	
	$scalex = imagesx($image) * $_xscale; //original scale image size //If the TAG size changes this will need to be scaled.
	$scaley = imagesy($image) * $_yscale; //original scale image size //If the TAG size changes this will need to be scaled.
	if($xchk > $ychk){
		$width = imagesx($watermark);
		$height = imagesy($watermark);
		$nWidth = $width * $scaley;
		$nHeight = $height * $scaley;
		$watermark = resizeimage($watermark, $width, $height, $nWidth, $nHeight);
	}else{
		$width = imagesx($watermark);
		$height = imagesy($watermark);
		$nWidth = $width * $scalex;
		$nHeight = $height * $scalex;
		$watermark = resizeimage($watermark, $width, $height, $nWidth, $nHeight);
	}
	
	// calculate the position of the watermark in the output image (the
	// watermark shall be placed in the lower right corner)
	$watermark_pos_x = imagesx($image) * ($_tref/100);
	$watermark_pos_y = imagesy($image) * ($_lref/100);
	
	if($watermark_pos_x > (imagesx($image) - imagesx($watermark)) ){
		$watermark_pos_x = imagesx($image) - imagesx($watermark);
	}
	if($watermark_pos_x > (imagesx($image) - imagesx($watermark)) ){
		$watermark_pos_y = imagesy($image) - imagesy($watermark);
	}
	
	// merge the source image and the watermark
	imagecopy($image, $watermark,  $watermark_pos_x, $watermark_pos_y, 0, 0,
	imagesx($watermark), imagesy($watermark));
	
	imagedestroy($watermark);
	return $image;
}

/**
 * Function takes an image uri, an image watermark(png) uri, top and left reference positions as a % of 100, to place the watermark.
 * @param Image URI $_img
 * @param Watermark URI $_wm
 * @param Width in pixels $_width
 * @param Height in pixels $_height
 */
function resize($_img, $_wref, $_width, $_height){
	 // load source image to memory
	$image = imagecreatefromfile(trim($_img,"/"));
    if (!$image) die('Unable to open image');
  
    $maxwidth = intval($_width);
    $maxheight = intval($_height);
    if ($maxwidth == 0 && $maxheight == 0) die('Unable to new scale');

  	$scalex = $maxwidth / imagesx($image); //original scale image size //If the TAG size changes this will need to be scaled.
  	$scaley = $maxheight / imagesy($image); //original scale image size //If the TAG size changes this will need to be scaled.
  	if($scalex < $scaley){
  		$width = imagesx($image);
	  	$height = imagesy($image);
	  	$nWidth = $width * $scalex;
	  	$nHeight = $height * $scalex;
 	 	$image = resizeimage($image, $width, $height, $nWidth, $nHeight);
	}else{
  		$width = imagesx($image);
  		$height = imagesy($image);
  		$nWidth = $width * $scaley;
  		$nHeight = $height * $scaley;
  		$image = resizeimage($image, $width, $height, $nWidth, $nHeight);
  	}

	return $image;
}

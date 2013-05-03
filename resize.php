<?php

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

  // load source image to memory
  $image = imagecreatefromfile(trim($_GET['image'],"/"));
  if (!$image) die('Unable to open image');
  
  $maxwidth = intval($_GET['maxwidth']);
  $maxheight = intval($_GET['maxheight']);
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
  
  // calculate the position of the watermark in the output image (the
  // watermark shall be placed in the lower right corner)
  /*$watermark_pos_x = imagesx($image) - imagesx($watermark) - 8;
  $watermark_pos_y = imagesy($image) - imagesy($watermark) - 10;*/
  $watermark_pos_x = 0;
  $watermark_pos_y = 0;

  // output watermarked image to browser
  header('Content-Type: image/png');
  imagejpeg($image, '', 100);  // use best image quality (100)

  // remove the images from memory
  imagedestroy($image);

?>
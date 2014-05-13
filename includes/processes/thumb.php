<?php

if(!file_exists('file://' . $_SERVER['DOCUMENT_ROOT']."uploads/".$_REQUEST['file']) ){
	header("HTTP/1.0 404 Not Found");
	die("Invalid file");
}
include 'includes/class/crop.image.to.square.class.php';

$crop = new Crop_Image_To_Square;
$crop->source_image = 'uploads/'.$_REQUEST['file'];

$crop->save_to_folder = 'thumbs/';
$crop->save_to_folderm = 'medium/';

/* left, center or right; If none is set, center will be used as default */
$process = $crop->crop('center');

if($process['result'])
{
echo 'The rectangle image (<em>'.$process['new_file_path'].'</em>) was cropped.';
}
?>

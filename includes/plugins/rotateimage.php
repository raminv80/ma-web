<?php
if($_SERVER['REMOTE_ADDR'] == '150.101.230.130' || $_SERVER['REMOTE_ADDR'] == '202.6.152.24'){
	
	if(empty($_POST['image'])){
		die('No image.');
	}
	include 'rotate.image.90.class.php';
	try{
		$rotate = new Rotate_Image_90;
		$rotate->source_image = 'medium/'.$_POST['image'];
		$process = $rotate->rotate(-90);
		$rotate->source_image = 'thumbs/'.$_POST['image'];
		$process = $rotate->rotate(-90);
		/* left, center or right; If none is set, center will be used as default */
	}catch(Exception $e){
		echo 'Exception throw: '.$e.'<br/>';
		continue;
	}
}




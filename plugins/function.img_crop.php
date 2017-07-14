<?php 
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:	function.img_crop.php
 * Type:	function
 * Name:	thumbnail
 * Purpose:	create shink and crop to square version of the image
 * -------------------------------------------------------------
 */
include '/includes/plugins/crop.image.to.square.class.php';

function smarty_function_img_crop($params, &$smarty)
{
	$src = $_SERVER['DOCUMENT_ROOT'];
	if(empty($params['data'])){
		return '';
	}
	try {
		$fname = basename($params['data']);
		if( !file_exists("/images/thumbs/{$fname}") ){
			try{
				if (!file_exists("/images/thumbs")) {
					mkdir("/images/thumbs", 0777, true);
				}
				$crop = new Crop_Image_To_Square;
				$crop->source_image = $params['data'];
				$crop->save_to_folder = "/images/thumbs";
				// 	$crop->save_to_folderm = "/images/medium/";
				/* left, center or right; If none is set, center will be used as default */
				$process = $crop->crop('center',$params['size']);
			}catch(Exception $e){
				return '';
			}
		}
	}
	catch(Exception $e) {
		return '';
	}
    
    return "/images/thumbs/{$fname}";
}
?>
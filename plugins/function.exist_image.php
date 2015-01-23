<?php 
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:	function.exist_image.php
 * Type:	function
 * Name:	exist_image
 * Purpose:	Verify whether the image exist, otherwise return default image.
 * -------------------------------------------------------------
 */
function smarty_function_exist_image($params, &$smarty)
{		
		$arr = explode('?', $params['image']);
		$filename = urldecode(trim($arr[0],'/'));
		if(!empty($params['image']) && file_exists( $_SERVER['DOCUMENT_ROOT']."{$filename}")){
			return $params['image'];
		}
    return $params['default'];
}
?>

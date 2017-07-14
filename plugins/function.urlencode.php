<?php 
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:	function.urlencode.php
 * Type:	function
 * Name:	urlencode
 * Purpose:	urlencode text
 * -------------------------------------------------------------
 */
function smarty_function_urlencode($params, &$smarty)
{
	if(empty($params['data'])){
		return '';
	}
	try {
		$output = str_replace(' ','-',$params['data']);
		$output = preg_replace("/[\s\W]+/", "_", strtolower($output));
		$output = trim($output,"_");
	}
	catch(Exception $e) {
		return '';
	}
    
    return $output;
}
?>
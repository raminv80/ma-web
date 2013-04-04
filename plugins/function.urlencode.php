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
		trigger_error("assign: missing 'data' parameter");
		return '';
	}
	try {
		$output = str_replace(' ','-',$params['data']);
		$output = preg_replace("/[\s\W]+/", "-", strtolower($output));
	}
	catch(Exception $e) {
	    trigger_error("assign: 'data' not in a recognised");
		return '';
	}
    
    return $output;
}
?>
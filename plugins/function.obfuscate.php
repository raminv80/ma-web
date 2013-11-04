<?php 
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:	function.obfuscate.php
 * Type:	function
 * Name:	obfuscate
 * Purpose:	outputs wraps email address in a mailto: with the email address obfusticated so that bots cannot easily read it.
 * -------------------------------------------------------------
 */
function smarty_function_obfuscate($params, &$smarty)
{
	$output = '';
	if(empty($params['email'])){
		//trigger_error("assign: missing 'email' parameter");
		return '';
	}
	
	try {
			$parts = explode('@',$params['email']);
			$user = $parts[0];
			$domain = $parts[1];
			$output = "<a href='javascript:void(0)' onclick='this.href=\"mailto:\" + \"{$user}\" + \"&#x40;\" + \"{$domain}\"' style='unicode-bidi:bidi-override;direction:rtl;-moz-user-select: none;-webkit-user-select: none;-ms-user-select: none;-o-user-select: none;user-select: none;'>".strrev($params['email'])."</a>";
	}catch(Exception $e) {
	    //trigger_error("error on banner plugin");
		return '';
	}
    
    return $output;
}
?>

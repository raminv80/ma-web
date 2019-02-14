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
		return '';
	}
	
	$visible_content = strrev($params['email']);
	$style = " style='unicode-bidi:bidi-override;direction:rtl;-moz-user-select: none;-webkit-user-select: none;-ms-user-select: none;-o-user-select: none;user-select: none;'";
	$attr = empty($params['attr'])?"":" {$params['attr']}";
	
	if(!empty($params['visible_content'])){
		$visible_content = $params['visible_content'];
		$style = '';
	}
	if(!empty($params['event'])){
	  $event = ";".$params['event'];
	}
	
	
	try {
			$parts = explode('@',$params['email']);
			$user = $parts[0];
			$domain = $parts[1];
			$output = "<a href='javascript:void(0)' onclick=\"this.href='mailto:' + '{$user}' + '&#x40;' + '{$domain}'{$event}\"{$attr}{$style}>{$visible_content}</a>";
	}catch(Exception $e) {
		return '';
	}
    
  return $output;
}
?>

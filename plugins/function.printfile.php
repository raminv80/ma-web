<?php 
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:	function.banner.php
 * Type:	function
 * Name:	banner
 * Purpose:	outputs formatted data for banner field
 * -------------------------------------------------------------
 */
function smarty_function_printfile($params, &$smarty)
{
	if(empty($params['file']) || empty($params['type'])){
		return '';
	}
	
	if(!file_exists($_SERVER['DOCUMENT_ROOT'].$params['file'])){
		return '';
	}
	
	try {
		
		$output = "<".$params['type'].">";
		$output.= file_get_contents($_SERVER['DOCUMENT_ROOT'].$params['file']);
		$output.= "</".$params['type'].">";

	}catch(Exception $e) {
		return '';
	}
    
    return $output;
}

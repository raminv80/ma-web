<?php 
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:	function.getvaluename.php
 * Type:	function
 * Name:	getvaluename
 * Purpose:	Get value from array option list using id
 * -------------------------------------------------------------
 */
function smarty_function_getvaluename($params, &$smarty)
{
	if(empty($params['id']) || empty($params['options'])){
		return '';
	}
	
	$optList = array();
	foreach ($params['options'] as $opt) {
		$optList[$opt['id']] = $opt['value'];
	}
    
    return $optList[$params['id']];
}
?>
<?php 
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:	function.msyql_to_aus_date.php
 * Type:	function
 * Name:	msyql_to_aus_date
 * Purpose:	outputs formatted date
 * -------------------------------------------------------------
 */
function smarty_function_msyql_to_aus_date($params, &$smarty)
{
	if(empty($params['datetime'])){
		return '';
	}
	$format = "d/m/Y";
	switch ($params['format']){
		case 'string';
			$format = "dS F Y";
			break;
		case 'javascript';
			$format = "m-d-Y";
			break;
		default:
			$format = "d-m-Y";
			break;
	}
	
	
	try {
	    $output = date($format, strtotime(str_replace("-","/",$params['datetime'])));
	}catch(Exception $e) {
		return '';
	}
    
    return $output;
}
?>
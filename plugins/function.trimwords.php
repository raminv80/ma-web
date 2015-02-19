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
function smarty_function_trimwords($params, &$smarty)
{
	if(empty($params['data'])){
		trigger_error("assign: missing 'data' parameter");
		return '';
	}
	if(empty($params['maxwords'])){
		return $params['data'];
	}
	try {
		$output='';
		$string = explode(" ", $params['data']);
		$i = 0;
		while ($i < $params['maxwords'])
		{
			$output.= $string[$i]." ";
			$i++;
		}
		if($string[$i]!=null){
			$output.= "...";
		}
	}
	catch(Exception $e) {
		return $params['data'];
	}
    
    return $output;
}
?>
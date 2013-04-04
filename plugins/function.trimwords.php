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
		trigger_error("assign: missing 'maxwords' parameter");
		return '';
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
	    trigger_error("assign: 'data' not in a recognised");
		return '';
	}
    
    return $output;
}
?>
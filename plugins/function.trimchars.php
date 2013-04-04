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
function smarty_function_trimchars($params, &$smarty)
{
	if(empty($params['data'])){
		trigger_error("assign: missing 'data' parameter");
		return '';
	}
	if(empty($params['maxchars'])){
		trigger_error("assign: missing 'maxwords' parameter");
		return '';
	}
	try {
		$count= 0 ;
		if(strlen($params['data']) > intval($params['maxchars'])){
			while($last != ' '){
				$output = substr($params['data'], 0,intval($params['maxchars'])-$count);
				$last = substr($output, -1,1);
				$count++;
			}
			$output.= "...";
		}else{
			$output=$params['data'];
		}
	}
	catch(Exception $e) {
	    trigger_error("assign: 'data' not in a recognised");
		return '';
	}
    
    return $output;
}
?>
<?php 
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:	function.striptrimwords.php
 * Type:	function
 * Name:	striptrimwords
 * Purpose:	Strip tags(except <p>) from text and then trim words to X number
 * -------------------------------------------------------------
 */
function smarty_function_striptrimwords($params, &$smarty)
{
	if(empty($params['data'])){
		//trigger_error("assign: missing 'data' parameter");
		return '';
	}
	if(empty($params['maxwords'])){
		return $params['data'];
	}
	try {
		$output='';
		$string = explode(" ", strip_tags($params['data'],"<p>"));
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
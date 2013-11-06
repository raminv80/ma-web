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
function smarty_function_trimsocialcontent($params, &$smarty)
{

	try {
		$output='';
		$arr = explode("#", $params['data']);
		$output = implode(' #', $arr);
		$output = str_replace('"', "", $output);
		$output = str_replace("'", "", $output);
		$output = utf8_encode(trim($output));

	}
	catch(Exception $e) {
		return '';
	}

    return $output;
}
?>

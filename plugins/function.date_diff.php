<?php 
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:	function.date_diff.php
 * Type:	function
 * Name:	urlencode
 * Purpose:	urlencode text
 * -------------------------------------------------------------
 */
function smarty_function_date_diff($params, &$smarty)
{
	if(empty($params['date_start']) || empty($params['date_end'])){
		return '0';
	}
	try {
		$datetime1 = date_create($params['date_start']);
		$datetime2 = date_create($params['date_end']);
		$interval = date_diff($datetime1, $datetime2);
		$differenceFormat = empty($params['date_format'])?'%R%a':$params['date_format'];
		$output = $interval->format($differenceFormat);
	}
	catch(Exception $e) {
		return '-';
	}
  return $output;
}
?>
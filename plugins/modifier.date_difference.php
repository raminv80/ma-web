<?php
/**
 * Smarty date_difference modifier plugin
 * 
 * Type:     modifier
 * Name:     date_difference
 * Purpose:  Determine the date difference
 * 
 * @param string $start_date
 * @param string $end_date text
 * @param string $format  option to return right part of the separator
 * @return string 
 */
function smarty_modifier_date_difference($start_date, $end_date, $format='')
{
  if(empty($start_date) || empty($end_date)){
		return $start_date.'>'.$end_date.'>'.$format;
	}
	try {
		$datetime1 = date_create($start_date);
		$datetime2 = date_create($end_date);
		$interval = date_diff($datetime1, $datetime2);
		$differenceFormat = empty($format) ? '%R%a' : $format;
		return str_replace('+', '', $interval->format($differenceFormat));
	}
	catch(Exception $e) {
		return '-';
	}
} 

?>

<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty split_str modifier plugin
 * 
 * Type:     modifier<br>
 * Name:     split_string<br>
 * Purpose:  simple split_string
 * 
 * @param string $string  input string
 * @param string $separator separator text
 * @param string $get_right  option to return right part of the separator
 * @return string 
 */
function smarty_modifier_split_str($string, $separator, $get_right)
{
    $outputArr = explode($separator, $string, 2);
    return ($get_right == 'right')?end($outputArr):$outputArr[0];
} 

?>
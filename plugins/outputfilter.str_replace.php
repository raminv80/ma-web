<?php 
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:	postfilter.str_replace.php
 * Type:	postfilter
 * Name:	str_replace
 * Purpose:	outputs replaced string
 * -------------------------------------------------------------
 */
function smarty_function_banner($output, Smarty_Internal_Template $smarty)
{
    $arr = $smarty->getTemplateVars('databaseVars');
    return str_replace($arr['key'], $arr['value'], $output);
}
?>
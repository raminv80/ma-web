<?php 
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:	outputfilter.str_replace.php
 * Type:	outputfilter
 * Name:	str_replace
 * Purpose:	outputs replaced string
 * -------------------------------------------------------------
 */
function smarty_outputfilter_str_replace($output, Smarty_Internal_Template $smarty)
{
    $arr = $smarty->getTemplateVars('DATABASE_VARS');
    if(!empty($arr['find']) && !empty($arr['replace'])){
      $output =  str_replace($arr['find'], $arr['replace'], $output);
    }
    return $output;
}
?>
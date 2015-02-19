<?php 
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:	function.google_events.php
 * Type:	function
 * Name:	google_events
 * Purpose:	add google event tracking to doc links
 * -------------------------------------------------------------
 */
include '/database/utilities.php';

function smarty_function_google_events($params, &$smarty) {
  if(empty($params['data'])){
    return $params['data'];
  }
  
  try{
    $output = AppendEventTrackingToString($params['data']);
  }catch(Exception $e){
    return $params['data'];
  }
  
  return $output;
}
?>
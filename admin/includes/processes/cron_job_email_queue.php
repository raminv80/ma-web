<?php
//Check if file is called from cron command or from webserver
if(php_sapi_name() == 'cli' || empty($_SERVER['REMOTE_ADDR'])) {
  
  set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
  include_once 'admin/includes/functions/admin-functions.php';
  
  try{
  	echo 'Sending...';
  	if(sendBulkMail()) die(' Done!');
  }catch (Exception $e){
  
  }
  	
  die (' Error!');
}

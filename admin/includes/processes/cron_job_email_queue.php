<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject;

try{
	echo 'Sending...';
	if(sendBulkMail()) die(' Done!');
}catch (Exception $e){

}
	
die (' Error!');







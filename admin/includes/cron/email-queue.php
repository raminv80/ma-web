<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject;
try{
	echo 'Sending...';
	$cnt = sendBulkMail();
/* 	$filename = $_SERVER['DOCUMENT_ROOT'].'/cron_log/emailqueue.txt';
	$body = "Sent: {$cnt} - ".time(). PHP_EOL;
	file_put_contents($filename, $body, FILE_APPEND | LOCK_EX); */
	die(" Sent: $cnt" );
	
}catch (Exception $e){
}
die (' Error!');







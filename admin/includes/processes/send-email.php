<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject, $SMARTY;

if(checkToken('admin', $_POST["formToken"]) && !empty($_POST['email']) && !empty($_POST['subject']) && !empty($_POST['content'])){
		$to = $_POST['email'];
		$from = 'Steeline Website';
		$fromEmail = 'noreply@'. str_replace('www.', '', $_SERVER['HTTP_HOST']);
		$subject = $_POST['subject'];
		$body = $_POST['content'];
		$response = sendMail($to, $from, $fromEmail, $subject, $body);
		echo json_encode(array(
				"response" => $response
		));
}
die ();







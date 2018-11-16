<?php
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject, $SMARTY, $CONFIG;

if(checkToken('admin', $_POST["formToken"]) && !empty($_POST['email_id'])){
	$sql = "SELECT * FROM tbl_email_queue WHERE email_id = :id ";
	if($res = $DBobject->wrappedSql( $sql, array(':id' => $_POST["email_id"]))){
		$to = empty($_POST['email']) ? $res[0]['email_to'] : $_POST['email'];
		$from = (string) $CONFIG->company->name;
		$fromEmail = (string) $CONFIG->company->email_from;
		$subject = $res[0]['email_subject'];
		$body = $res[0]['email_content'];
		$bcc = getenv('EMAIL_ORDERS');
		$response = sendMail($to, $from, $fromEmail, $subject, $body, $bcc);
		echo json_encode(array(
				"response" => $response
		));
	}
	
}
die ();







<?php
require_once $_SERVER['DOCUMENT_ROOT'].'includes/createsend/csrest_subscribers.php';
set_include_path($_SERVER['DOCUMENT_ROOT']);
include "includes/functions/functions.php";

if(empty($_POST['name']) || empty($_POST['email']) ){
	$redirect=$_SERVER['HTTP_REFERER'];
	header("Location: {$redirect}");
	die();
}
	
	$wrap = new CS_REST_Subscribers('7db17c18a86cbc3a1c81a78de7c43926', '060d24d9003a77b06b95e7c47691975b');
	$result = $wrap->add(array(
			'EmailAddress' => $_POST['email'],
			'Name' => $_POST['name'],
			'CustomFields' => array(
					array(
							'Key' => 'Agegroup',
							'Value' => $_POST['age'],
					),
					array(
							'Key' => 'Postcode',
							'Value' => $_POST['postcode']
					)
			),
			'Resubscribe' => true
	));
	
	if($result->was_successful()) {
		global $DBobject;
		$content=serialize($_POST);
		$buf.='<h2>Cocolat - VIP Signup form</h2><br/><br/>';
		foreach ($_POST as $name => $var){
			$buf.='<br/><b>'.$name.': </b> <br/> '.$var.'<br/>';
		}
		$body = $buf;
		$subject = 'Cocolat - VIP Signup form';
		$fromEmail = 'noreply@cocolat.com.au';
		$from = 'Cocolat - Website';
		$to = 'nick@them.com.au';
		$sql="INSERT INTO tbl_form (form_date,form_data,form_email,form_action,form_post,form_sender_ip) VALUES (NOW(),:body,:to,'VIP',:content,:ip)";
		$params = array(
				":body"=>$body,
				":to"=>$to,
				":content"=>$content,
				":ip"=>$_SERVER['REMOTE_ADDR']
		);
		$DBobject->executeSQL($sql,$params);
		if(sendMail($to, $from, $fromEmail, $subject, $body)){
			header("Location: /thanks-vip");
			exit;
		}else{
			$_SESSION['error']='There is an error with your request. please try again later.';
			$redirect=$_SERVER['HTTP_REFERER'];
			header("Location: {$redirect}");
			exit;
		}
	} else {
		$_SESSION['error']='There is an error with your request. please try again later.';
		$redirect=$_SERVER['HTTP_REFERER'];
		header("Location: {$redirect}");
		exit;
	}
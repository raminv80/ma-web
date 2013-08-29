<?php
require_once $_SERVER['DOCUMENT_ROOT'].'includes/createsend/csrest_subscribers.php';
set_include_path($_SERVER['DOCUMENT_ROOT']);
include "includes/functions/functions.php";

if(empty($_POST['franchisename']) || empty($_POST['franchiseemail']) ){
	$redirect=$_SERVER['HTTP_REFERER'];
	header("Location: {$redirect}");
	die();
}
	
	$wrap = new CS_REST_Subscribers('2399ebbff75c07d2b430a9cf23138883', '060d24d9003a77b06b95e7c47691975b');
	$result = $wrap->add(array(
			'EmailAddress' => $_POST['franchiseemail'],
			'Name' => $_POST['franchisename'],
			'CustomFields' => array(
					array(
							'Key' => 'Phone',
							'Value' => $_POST['franchisephone'],
					),
					array(
							'Key' => 'Postcode',
							'Value' => $_POST['franchisepostcode']
					),
					array(
							'Key' => 'Enquiry',
							'Value' => $_POST['franchiseenquiry']
					)
			),
			'Resubscribe' => true
	));
	
	if($result->was_successful()) {
		global $DBobject;
		$content=serialize($_POST);
		$buf.='<h2>Cocolat - Franchise enquiry form</h2><br/><br/>';
		foreach ($_POST as $name => $var){
			$buf.='<br/><b>'.$name.': </b> <br/> '.$var.'<br/>';
		}
		$body = $buf;
		$subject = 'Cocolat - Franchise enquiry form';
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
			header("Location: /thanks-franchise");
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
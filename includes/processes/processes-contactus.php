<?php

if(checkToken('frontend',$_POST["formToken"], false)){
	$banned=array('formToken');
	$content=serialize($_POST);
	$buf.='<h2>Website contact - Contact Us form</h2><br/><br/>';
	foreach ($_POST as $name => $var){
		if(!in_array($name, $banned)){
			$buf.='<br/><b>'.$name.': </b> <br/> '.$var.'<br/>';
		}
	}
	$body = $buf;
	$subject = 'Website contact - Contact Us form';
	$fromEmail = 'noreply@' . str_replace ( "www.", "", $_SERVER ['HTTP_HOST'] );
	$from = 'Chemplus - Website';
	$to = 'apolo@them.com.au';
	$sql="INSERT INTO tbl_form (form_date,form_data,form_email,form_action,form_post,form_sender_ip) VALUES (NOW(),'".clean($body)."','".clean($to)."','ContactUs','".clean($content)."','".clean($_SERVER['REMOTE_ADDR'])."')";
	$DBobject->executeSQL($sql);
	if(sendMail($to, $from, $fromEmail, $subject, $body)){
		header("Location: /thank-you");
		exit;
	}else{
		$_SESSION['error']='There is an error with your request. please try again later.';
		$redirect=$_SERVER['HTTP_REFERER'];
		header("Location: {$redirect}");
		exit;
	}
}
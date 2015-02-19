<?php

$error = 'Missing required info. Please try again.';
if(checkToken('frontend',$_POST["formToken"], true) && empty($_POST['honeypot']) && (time() - $_POST['timestamp']) > 10 ){
  if(!empty($_POST['email']) && !empty($_POST['postcode'])  && !empty($_POST['enquiry'])){
  	require_once 'includes/createsend/csrest_subscribers.php';
  	global $CONFIG,$DBobject,$SMARTY,$SITE;
  	
  	$error = '';
  	$sent = 0;
  	try{
  		$banned=array('formToken','action', 'additional', 'wantpromo', 'enqsub','Hp','timestamp', 'honeypot');
  		$content=serialize($_POST);
  		$buf.='<h2>Website enquiry</h2>';
  		foreach ($_POST as $name => $var){
  			if(!in_array($name, $banned)){
  				$buf.='<br/><b>'.ucwords($name).': </b> <br/> '.$var.'<br/>';
  			}
  		}
  		$body = $buf;
  		$subject = 'Website contact';
  		$fromEmail = (string) $CONFIG->company->email_from;
  		$to = (string) $CONFIG->company->email_contact;
  		$COMP = json_encode($CONFIG->company);
  		$SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
  		$from = (string) $CONFIG->company->name;
  		$sent = sendMail($to, $from, $fromEmail, $subject, $body);
  	}catch (Exception $e){
  		$error = 'There was an error sending the contact email.';
  	}
  	
  	if(empty($error)){
  	  
  	  try{
  	    $SMARTY->assign('DOMAIN', "http://" . $_SERVER['HTTP_HOST']);
  	    $body = $SMARTY->fetch("email-thank-you.tpl");
  	    $subject = 'Thank you for your enquiry';
  	    $fromEmail = (string) $CONFIG->company->email_from;
  	    $to = $_POST['email'];
  	    $COMP = json_encode($CONFIG->company);
  	    $SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
  	    $from = (string) $CONFIG->company->name;
  	    $sent = sendMail($to, $from, $fromEmail, $subject, $body);
  	  }catch (Exception $e){
  	    $error = 'There was an error sending the contact email.';
  	  }
  	  
  		header("Location: /thank-you");
  		die();
  	}else{
  	  $_SESSION['error'] = $error;
  	  $redirect = $_SERVER['HTTP_REFERER'];
  	  header("Location: {$redirect}#error");
  	  die();
  	}
  }else{
    $_SESSION['error']='Please provide the required information.';
    $redirect=$_SERVER['HTTP_REFERER'];
    header("Location: {$redirect}");
    exit;
  }
}else{
  $_SESSION['error']='You do not have permission to submit this form. Please refresh the page and try again.';
  $redirect=$_SERVER['HTTP_REFERER'];
  header("Location: {$redirect}");
  die();
}

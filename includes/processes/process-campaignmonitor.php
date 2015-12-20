<?php

require_once 'includes/createsend/csrest_subscribers.php';
if(checkToken('frontend',$_POST["formToken"], true) && empty($_POST['honeypot']) && (time() - $_POST['timestamp']) > 10 ){

	$exceptions = "";
	foreach($_POST as $key => $p){
		$_SESSION["{$key}"] = $p;
	}
	
	try{
		//store on API
		$wrap = new CS_REST_Subscribers('1ad04e1c384a79cacd9ca47e8c3b8f5c', '7d6ddc2467944f2a174afd5eb05040b4');
		$customFields = array();
		//Retrieve Current Subscriptions
		$check_result = $wrap->get($_POST['submail']);
		if($check_result->was_successful()){
		  $cf = $check_result->response->CustomFields;
		  foreach($cf as $f){
		    if($f->Key==="Issues" && $f->Value != $_POST['issue']){
		      $customFields[]=array('Key'=>'Issues','Value'=>$f->Value);
		    }
		  }
		}
		//Assign New Issue Subscription
		if(!empty($_POST['issue'])){
		  $customFields[]=array('Key'=>'Issues','Value'=>$_POST['issue']);
		}
	
		//Assign Custom Field
		if(!empty($_POST['subpostcode'])){
		$customFields[]=array('Key'=>'postcode','Value'=>$_POST['subpostcode']);
		}
		
		$cs_result = $wrap->add(array(
				'EmailAddress' => $_POST['submail'],
				'Name' => $_POST['subname'],
				'CustomFields' => $customFields,
				"Resubscribe" => "true"
		));
	}catch(Exception $e){
		$exceptions += var_dump($e,1);
	}
	
	try{
		//Send email
		$buf ='<h2>Issues Subscription</h2><br/><br/>';
		foreach ($_POST as $name => $var){
			$buf.='<br/><b>'.$name.': </b> <br/> '.$var.'<br/>';
			$_SESSION['values'][$name]=$var;
		}

		$to = "nick@them.com.au";
		$from = 'PyneOnline';
		$fromEmail = "noreply@".str_replace("www.","",$GLOBALS['HTTP_HOST']);
		$subject = 'Issues Subscription';
		$body = $buf;
		
		sendMail($to, $from, $fromEmail, $subject, $body);
	}catch(Exception $e){
		$exceptions += var_dump($e,1);
	}
		
	if($cs_result->was_successful() && empty($exceptions)) {
	} else {
		try{
			//Send email
			$buf='<h2> - Exceptions</h2><br/>';
			foreach ($_POST as $name => $var){
				if (!(($name == 'x') ||($name == 'y'))){
					$buf.='<br/><b>'.$name.': </b> <br/> '.$var.'<br/>';
				}
			}
			$buf.="<br><br>EXCEPTIONS<br>{$exceptions}";
			$response = print_r($cs_result, true);
			$buf.="<br><br>CreateSend Response<br>{$response}";
				
			$to = "nick@them.com.au,apolo@them.com.au";
			$subject = $GLOBALS['HTTP_HOST'].' - Exceptions';
			$from = " Card";
			$fromEmail = "noreply@".str_replace("www.","",$GLOBALS['HTTP_HOST']);
			$body = $buf;
			sendMail($to, $from, $fromEmail, $subject, $body);
		}catch(Exception $e){}
		
		$_SESSION['error']='There is an error with your request. please try again later.';
		header("Location: {$_SERVER['HTTP_REFERER']}#error");
		exit;
	}

	header("Location: /thank-you");
	exit;
}else{
  $_SESSION['error']='You do not have permission to submit this form. Please refresh the page and try again.';
  header("Location: {$_SERVER['HTTP_REFERER']}#error");
  die();
}





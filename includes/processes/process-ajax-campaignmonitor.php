<?php

require_once 'includes/createsend/csrest_subscribers.php';
if(checkToken('frontend',$_POST["formToken"], true) && empty($_POST['honeypot']) && (time() - $_POST['timestamp']) > 10 ){
  $token = getToken('frontend');
	$exceptions = "";
	foreach($_POST as $key => $p){
		$_SESSION["{$key}"] = $p;
	}
	try{
		//store on API
		$wrap = new CS_REST_Subscribers('', '7d6ddc2467944f2a174afd5eb05040b4');
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
		
		
		echo json_encode(array("error"=>"There is an error with your request. please try again later.","status"=>false,"message"=>"","formtoken"=>$token));
		die;
	}
  
	echo json_encode(array("error"=>"","status"=>true,"message"=>"<div id='subthankyou'>Thank you for subscribing. Look out for the next edition of our e-newsletter.</div>","formtoken"=>$token));
	die;
}else{
  $token = getToken('frontend');
  echo json_encode(array("error"=>"You do not have permission to submit this form. Please refresh the page and try again.","status"=>false,"message"=>"","formtoken"=>$token));
	die;
}





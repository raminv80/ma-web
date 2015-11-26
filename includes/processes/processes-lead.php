<?php

$error = 'Missing required info. Please try again.';
if(checkToken('frontend',$_POST["formToken"], true) && empty($_POST['honeypot']) && (time() - $_POST['timestamp']) > 10 ){
  if(!empty($_POST['email']) && !empty($_POST['phone'])  && !empty($_POST['name'])   && !empty($_POST['message'])){
  	global $CONFIG,$DBobject,$SMARTY,$SITE,$GA_ID;
  	
  	$error = '';
  	$sent = 0;
  	
  	//SAVE FILE FUNCTIONS (Accepts Image types)
  	$file_name = "";
  	try{
  		if(!empty($_FILES["file"]["name"])){
  			if($_FILES["file"]["error"] == 0){
  				if(($_FILES["file"]["type"] == "application/pdf") || strtolower(substr($_FILES["file"]["name"],- 3)) == 'pdf' || strtolower(substr($_FILES["file"]["name"],- 3)) == 'jpg' || strtolower(substr($_FILES["file"]["name"],- 3)) == 'png' || strtolower(substr($_FILES["file"]["name"],- 3)) == 'gif'){
  					//$path = getcwd() . '/';
  					$path =  $_SERVER['DOCUMENT_ROOT'];
  					$file_short = time() . '_' . str_replace(" ",'',$_FILES["file"]["name"]);
  					$_POST['filename'] = $file_short;
  					$file_name = $path . "uploads_lead/" . $file_short;
  					if(move_uploaded_file($_FILES["file"]["tmp_name"],$file_name)){
  					}
  				}else{
  					$error = 'Please check your file extension (use PDF, JPG , GIF or  PNG).';
  				}
  			}else{
  				$error = 'Please check your file and  try again later.';
  			}
  		}
  	}catch(Exception $e){
  		$error = 'Please check your file and  try again later.';
  	}
  	
  	
  	//SEND EMAIL TO ADMIN	
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
  		$subject = 'Website lead';
  		$fromEmail = (string) $CONFIG->company->email_from;
  		$to = (string) $CONFIG->company->email_lead;
  		$COMP = json_encode($CONFIG->company);
  		$SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
  		$from = (string) $CONFIG->company->name;
  		$bcc = null;
  		if(!empty($file_name)) {
  			sendAttachMail($to,$from,$fromEmail,$subject,$body,$bcc,$file_name);
  		}else{
  			$sent = sendMail($to, $from, $fromEmail, $subject, $body, $bcc);
  		}
  		
  	}catch (Exception $e){
  		$error = 'There was an error sending your enquiry.';
  	}

  	//SAVE IN DATABASE
  	try{
  		$sql = "INSERT INTO tbl_lead (lead_site,lead_form_name,lead_reference_id,lead_reference_name,lead_name,lead_email,lead_phone,lead_postcode,lead_file,lead_enquiry,lead_content1,lead_content2,lead_flag1,lead_flag2,lead_ip,lead_sent,lead_created)
            VALUES (:lead_site,:lead_form_name,:lead_reference_id,:lead_reference_name,:lead_name,:lead_email,:lead_phone,:lead_postcode,:lead_file,:lead_enquiry,:lead_content1,:lead_content2,:lead_flag1,:lead_flag2,:lead_ip,:lead_sent,now() )";
  		$params = array(":lead_name"=>$_POST['name'],
  				":lead_site"=>$SITE,
  				":lead_form_name"=>$_POST['form_name'],
  				":lead_reference_id"=>'',
  				":lead_reference_name"=>$_POST['brand_interested_in'],
  				":lead_name"=>$_POST['name'],
  				":lead_email"=>$_POST['email'],
  				":lead_phone"=>$_POST['phone'],
  				":lead_postcode"=>$_POST['postcode'],
  				":lead_file"=> empty($file_short)?"":"uploads_lead/" . $file_short,
  				":lead_enquiry"=>$_POST['message'],
  				":lead_content1"=> $_POST['jobtitle'],
  				":lead_content2"=> $_POST['companyname'],
  				":lead_flag1"=> '',
  				":lead_flag2"=> '',
  				":lead_ip"=>$_SERVER['REMOTE_ADDR'],
  		    ":lead_ga_clientid"=>gaParseCookie(),
  				":lead_sent"=>$sent);
  		$DBobject->wrappedSql($sql,$params);
  	}catch(Exception $e){
  		$error = 'There was an unexpected error saving your enquiry.';
  	}
  	
  	if(empty($error)){
  	  /* try{
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
  	    $error = 'There was an error sending the lead email.';
  	  } */
  		
  		sendGAEvent($GA_ID,'Enquiry', 'Submitted', $_POST['form_name']);
  		
  		header("Location: /thank-you");
  		die();
  	}
  }else{
    $error = 'Please provide the required information.';
  }
}else{
  $error = 'You do not have permission to submit this form. Please refresh the page and try again.';
}

$_SESSION['post'] =  $_POST;
$_SESSION['error'] =  $error;
header("Location: {$_SERVER['HTTP_REFERER']}#error");
die();
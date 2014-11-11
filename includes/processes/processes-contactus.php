<?php

if(checkToken('frontend',$_POST["formToken"], false)){
  switch($_POST["action"]){
    case 'question':
      global $DBobject,$SITE;
      $error = '';
      
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
              $file_name = $path . "uploads_contact/" . $file_short;
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
      $sent = 0;
      try{
        $banned=array('formToken','action','redirect');
        //Send email
        $buf='<h2>Website contact - Contact Us form</h2>';
        foreach ($_POST as $name => $var){
           if(!in_array($name, $banned)){
            $buf.='<br/><b>'.ucwords($name).': </b> <br/> '.$var.'<br/>';
          }
        }
      
        
        $to = "apolo@them.com.au";

        $subject = 'Website contact - Contact Us form';
        $body = '<img src="http://'. $_SERVER ['HTTP_HOST']. '/images/logo.png" alt="logo">' .$buf;
        $from = 'Website';
        $fromEmail = "noreply@" . str_replace ( "www.", "", $_SERVER ['HTTP_HOST'] );
        $sent = sendAttachMail($to, $from, $fromEmail, $subject, $body, '', $file_name);
      }catch(Exception $e){
        $error .= 'There was an error sending the contact email.';
      }
/*       
      try{
        $sql = "INSERT INTO tbl_contact (contact_name,contact_site,contact_email,contact_phone,contact_method,contact_postcode,contact_store_id,contact_file,contact_details,contact_ip,contact_sent)
            VALUES (:contact_name,:contact_site,:contact_email,:contact_phone,:contact_method,:contact_postcode,:contact_store_id,:contact_file,:contact_details,:contact_ip,:contact_sent)";
        $params = array(":contact_name"=>$_POST['name'],
            ":contact_site"=>$SITE,
            ":contact_email"=>$_POST['email'],
            ":contact_phone"=>$_POST['phone'],
            ":contact_method"=>$_POST['contact-method'],
            ":contact_postcode"=>$_POST['postcode'],
            ":contact_store_id"=>$_POST['listing_id'],
            ":contact_file"=> "uploads_contact/" . $file_short,
            ":contact_details"=>$_POST['comments'],
            ":contact_ip"=>$_SERVER['REMOTE_ADDR'],
            ":contact_sent"=>$sent);
        $DBobject->wrappedSql($sql,$params);
      }catch(Exception $e){
        $error = 'There was an unexpected error saving your question.';
      } */
      if(!empty($error)){
      	$_SESSION['error'] = $error;
      	header("Location: {$_SERVER['HTTP_REFERER']}#error");
      }else{
      	header("Location: /thank-you");
      }
      
      exit;
  }
}


/* // standard contact process without attachment

$error = 'Missing required info. Please try again.';
if(checkToken('frontend',$_POST["formToken"], false) && !empty($_POST['product_id']) && !empty($_POST['email']) && !empty($_POST['postcode']) && empty($_POST['additional'])){
	require_once 'includes/createsend/csrest_subscribers.php';
	global $DBobject,$SMARTY,$SITE;
	
	$error = '';
	$sent = 0;
	//============= ADD IN CREATE-SEND
	if($_POST['wantpromo']){
		try{
			$wrap = new CS_REST_Subscribers('067b9df15ecf7032eef9eb328942e410', '060d24d9003a77b06b95e7c47691975b');
			$cs_result = $wrap->add(array(
					'EmailAddress' => $_POST['email'],
					'Name' => $_POST['gname'],
					'CustomFields' => array(
							array(
									'Key' => 'Surname ',
									'Value' => $_POST['surname '],
							),
							array(
									'Key' => 'Phone',
									'Value' => $_POST['phone'],
							),
							array(
									'Key' => 'Postcode',
									'Value' => $_POST['postcode'],
							)
					),
					"Resubscribe" => "true"
			));
		}catch(Exception $e){}
	}
	try{
		$banned=array('formToken','action', 'additional', 'wantpromo', 'enqsub');
		$content=serialize($_POST);
		$buf.='<h2>Product enquiry</h2>';
		foreach ($_POST as $name => $var){
			if(!in_array($name, $banned)){
				$buf.='<br/><b>'.ucwords($name).': </b> <br/> '.$var.'<br/>';
			}
		}
		$body = $buf;
		$subject = 'Website contact - Product enquiry';
		$fromEmail = 'noreply@' . str_replace ( "www.", "", $_SERVER ['HTTP_HOST'] );
		$from = 'Toro Certified Pre-Owned Equipment';
		$to = 'apolo@them.com.au';
		
		$sent = sendMail($to, $from, $fromEmail, $subject, $body);
	}catch (Exception $e){
		$error = 'There was an error sending the contact email.';
	}
	
	//============= INSERT RECORD IN DB
	try{
		$sql = "INSERT INTO tbl_contact (contact_gname,contact_surname,contact_email,contact_phone,contact_postcode,contact_product_object_id,contact_enquiry,contact_ip,contact_sent)
            VALUES (:contact_gname,:contact_surname,:contact_email,:contact_phone,:contact_postcode,:contact_product_object_id,:contact_enquiry,:contact_ip,:contact_sent)";
		$params = array(":contact_gname"=>$_POST['gname'],
				":contact_surname"=>$_POST['surname'],
				":contact_email"=>$_POST['email'],
				":contact_phone"=>$_POST['phone'],
				":contact_postcode"=>$_POST['postcode'],
				":contact_product_object_id"=>$_POST['product_id'],
				":contact_enquiry"=>$_POST['enquiry'],
				":contact_ip"=>$_SERVER['REMOTE_ADDR'],
				":contact_sent"=>$sent);
		$DBobject->wrappedSql($sql,$params);	
	}catch(Exception $e){
		$error = 'There was an unexpected error saving your enquiry.';
	}
	
	if(empty($error)){
		header("Location: /thank-you");
		exit;
	}
	$error = 'There is an unknown error with your request. Please try again later.';
} 
$_SESSION['error'] = $error;
$redirect = $_SERVER['HTTP_REFERER'];
header("Location: {$redirect}#error");
exit;
*/
<?php
session_start();
//Fatal Error Handler
register_shutdown_function( "fatal_handler" );
function fatal_handler() {
	$errfile = "unknown file";
	$errstr  = "shutdown";
	$errno   = E_CORE_ERROR;
	$errline = 0;
	$error = error_get_last();
	if( $error !== NULL) {
		$errno   = $error["type"];
		$errfile = $error["file"];
		$errline = $error["line"];
		$errstr  = $error["message"];
		
		$to = "nick@them.com.au";
		$from = "noreply@".str_replace("www.","",$_SERVER['HTTP_HOST']);
		$fromEmail = "noreply@".str_replace("www.","",$_SERVER['HTTP_HOST']);
		$subject = "Fatal Error - ";
		$trace = print_r( debug_backtrace( false ), true );
		$body  = "<table><thead bgcolor='#c8c8c8'><th>Item</th><th>Description</th></thead><tbody>";
		$body .= "<tr valign='top'><td><b>Error</b></td><td><pre>$errstr</pre></td></tr>";
		$body .= "<tr valign='top'><td><b>Errno</b></td><td><pre>$errno</pre></td></tr>";
		$body .= "<tr valign='top'><td><b>File</b></td><td>$errfile</td></tr>";
		$body .= "<tr valign='top'><td><b>Line</b></td><td>$errline</td></tr>";
		$body .= "<tr valign='top'><td><b>Trace</b></td><td><pre>$trace</pre></td></tr>";
		$body .= '</tbody></table>';
		$body .= serialize($_POST);
		/* To send HTML mail, you can set the Content-type header. */
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		/* additional headers */
		$headers .= "From: ". $from . " <".$fromEmail.">\r\n";
		$headers .= "Bcc: cmsemails@them.com.au\r\n";
		ini_set('sendmail_from', $fromEmail);
		$mailSent = mail($to,$subject,$body,$headers);
		ini_restore('sendmail_from');
		$_SESSION['error']='We had trouble saving your entry. Please review your entry and try again. If this continues please contact us.';
		header("Location: error.php");
		die();
	}
}

require_once 'createsend/csrest_subscribers.php';
require_once 'email-class.php';

if(!empty($_POST)){

	foreach($_POST as $key => $p){
		$_SESSION["{$key}"] = $p;
	}
	
	try{
		//store on API
		$wrap = new CS_REST_Subscribers('', '060d24d9003a77b06b95e7c47691975b');
		
		while(true){
			$check_result = $wrap->get($_POST['email']);
			if($check_result->was_successful()){
				$_SESSION['error']='The email you are using address already exists';
			}else{
				break;
			}
			$_POST['email'] = ''.time().'.'.$_POST['email'];
		}
		
		$cs_result = $wrap->add(array(
				'EmailAddress' => $_POST['email'],
				'Name' => $_POST['fname'].'  '. $_POST['lname'],
				'CustomFields' => array(
						array(
								'Key' => 'Daytimephone',
								'Value' => $_POST['hphone'],
						),
						array(
								'Key' => 'Mobilephone',
								'Value' => $_POST['Mobilephone'],
						),
						array(
								'Key' => 'Address',
								'Value' => $_POST['address'],
						),
						array(
								'Key' => 'Suburb',
								'Value' => $_POST['suburb'],
						),
						array(
								'Key' => 'State',
								'Value' => $_POST['state'],
						),
						array(
								'Key' => 'Postcode',
								'Value' => $_POST['postcode'],
						),
						array(
								'Key' => 'Serial',
								'Value' => $_POST['serialno'],
						),
						array(
								'Key' => 'Store',
								'Value' => $_POST['store'],
						),
						array(
								'Key' => 'Receiptfile',
								'Value' => $file_short,
						),
						array(
								'Key' => 'Purchasedate',
								'Value' => $_POST['dpurchased'].'/'.$_POST['mpurchased'].'/'.$_POST['ypurchased'],
						),
						array(
								'Key' => 'SerialNo.',
								'Value' => $_POST['serialno'],
						),
	
						array(
								'Key' => 'Aboutthispromotion',
								'Value' => $_POST['Aboutthispromotion'],
						),
	
						array(
								'Key' => 'Noticedin-storeAd',
								'Value' => $_POST['Noticedin-storeAd'],
						),
						array(
								'Key' => 'Acceptpromotionalmaterial',
								'Value' => $_POST['Acceptpromotionalmaterial']
						),
						array(
								'Key' => 'Product',
								'Value' => $_POST['Product']
						)
				)
		));
	}catch(Exception $e){
		$exceptions += var_dump($e,1);
	}
	
	try{
		//Send email
		$content=serialize($_POST);
		$buf.='<h2> subscription</h2><br/><br/>';
		foreach ($_POST as $name => $var){
			$buf.='<br/><b>'.$name.': </b> <br/> '.$var.'<br/>';
			$_SESSION['values'][$name]=$var;
		}

		$sendEmail = new email();
		$sendEmail->to = "cmsemails@them.com.au";//"belinda@them.com.au";
		//$sendEmail->bcc = "cmsemails@them.com.au";
		$sendEmail->subject = ' subscription - enquiry form';
		$sendEmail->body = $buf;
		//$sendEmail->attachementFile = "/uploads/summer-rewards/" .$file_short;
		$sendEmail->attachementFile = "\\uploads\\" .$file_short;
		//print_r($sendEmail);
		$result = $sendEmail->sendEmail();
	}catch(Exception $e){
		$exceptions += var_dump($e,1);
	}
		
	if($cs_result->was_successful() && empty($exceptions)) {
	} else {
		try{
			//Send email
			$content=serialize($_POST);
			$buf='<h2> - Exceptions</h2><br/>';
			foreach ($_POST as $name => $var){
				if (!(($name == 'x') ||($name == 'y'))){
					$buf.='<br/><b>'.$name.': </b> <br/> '.$var.'<br/>';
				}
			}
			$buf.="<br><br>EXCEPTIONS<br>{$exceptions}";
			$response = var_dump($cs_result->response,1);
			$buf.="<br><br>CreateSend Response<br>{$response}";
				
			$sendEmail = new email();
			$sendEmail->to = "nick@them.com.au,apolo@them.com.au";
			$sendEmail->bcc = "cmsemails@them.com.au";
			$sendEmail->subject = ' - Exceptions';
			$sendEmail->fromName = " Card";
			$sendEmail->fromEmail = "noreply@".str_replace("www.","",$_SERVER['HTTP_HOST']);
			$sendEmail->body = $buf;
			$result = $sendEmail->sendEmail();
		}catch(Exception $e){}
		
		$_SESSION['error']='There is an error with your request. please try again later.';
		header("Location: error.php");
		exit;
	}

	header("Location: thank-you.php");
	exit;
}



<?php

require_once 'includes/createsend/csrest_subscribers.php';

if(!empty($_POST)){

	$exceptions = "";
	foreach($_POST as $key => $p){
		$_SESSION["{$key}"] = $p;
	}
	
	try{
		//store on API
		$wrap = new CS_REST_Subscribers('', '060d24d9003a77b06b95e7c47691975b');
		//// COMPETITIONS ONLY. CHECK FOR EXISTING EMAIL AND THEN PREPEND WITH TIMESTAMP
// 		while(true){
// 			$check_result = $wrap->get($_POST['email']);
// 			if($check_result->was_successful()){
// 				$_SESSION['error']='The email you are using address already exists';
// 			}else{
// 				break;
// 			}
// 			$_POST['email'] = ''.time().'.'.$_POST['email'];
// 		}
		
		$cs_result = $wrap->add(array(
				'EmailAddress' => $_POST['email'],
				'Name' => $_POST['fullname'],
				"Resubscribe" => "true",
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
		$buf ='<h2>e-newsletter Subscription</h2><br/><br/>';
		foreach ($_POST as $name => $var){
			$buf.='<br/><b>'.$name.': </b> <br/> '.$var.'<br/>';
			$_SESSION['values'][$name]=$var;
		}

		$to = "apolo@them.com.au";
		$from = 'eShop';
		$fromEmail = "noreply@".str_replace("www.","",$_SERVER['HTTP_HOST']);
		$subject = 'e-newsletter Subscription';
		$body = $buf;
		
		sendMail($to, $from, $fromEmail, $subject, $body);
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
			$response = print_r($cs_result, true);
			$buf.="<br><br>CreateSend Response<br>{$response}";
				
			$to = "nick@them.com.au,apolo@them.com.au";
			$subject = $_SERVER['HTTP_HOST'].' - Exceptions';
			$from = " Card";
			$fromEmail = "noreply@".str_replace("www.","",$_SERVER['HTTP_HOST']);
			$body = $buf;
			sendMail($to, $from, $fromEmail, $subject, $body);
		}catch(Exception $e){}
		
		$_SESSION['error']='There is an error with your request. please try again later.';
		header("Location: {$_SERVER['HTTP_REFERER']}#send");
		exit;
	}

	header("Location: /thank-you");
	exit;
}





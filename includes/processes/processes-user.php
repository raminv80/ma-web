<?php
if(!empty($_POST["formToken"]) && checkToken('frontend',$_POST["formToken"],false)){

	/************** SET CAMPAIGN MONITOR LIST ID WHEN NEEDED *********/
	$LIST_ID = '';
	/************** **************************************** *********/
	
	switch($_POST["action"]){
    case 'create':
    	$_POST['want_promo'] = empty($_POST['want_promo']) ? 0 : 1;
    	SetMemberCampaignMonitor($LIST_ID, $_POST, $_POST['want_promo']); 
    	
      $user_obj = new UserClass();
      $_POST['username'] = $_POST['email'];
      $res = $user_obj->Create($_POST);
      if($res['error']){
        echo json_encode(array(
            'error'=>$res['error'],
            'url'=>null
        ));
      }else{
        $_SESSION['user']['public'] = $res;
        $cart_obj = new cart();
        $cart_obj->SetUserCart($res['id']);
        $url = $_SERVER['HTTP_REFERER'];
        if($_POST['redirect']){
          $url = $_POST['redirect'];
        }
        
        try{
        	// SEND CONFIRMATION EMAIL
        	$SMARTY->assign("DOMAIN",'http://'.$HTTP_HOST);
        	$COMP = json_encode($CONFIG->company);
        	$SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
        	$SMARTY->assign("name",$res['gname']);
        	$SMARTY->assign("username",$_POST['email']);
        	$SMARTY->assign("password",$_POST['password']);
        	
        	$buffer= $SMARTY->fetch('email/welcome.tpl');
        	$to = $_SESSION['user']['public']['email'];
        	$from = (string) $CONFIG->company->name;
        	$fromEmail = "noreply@" . str_replace ( "www.", "", $HTTP_HOST );
        	$subject = "{$from} | New Membership";
        	$body = $buffer;
        	$mailID = sendMail($to, $from, $fromEmail, $subject, $body, null, $res['id']);
        	
        }catch(Exception $e){
        	echo json_encode(array(
        			'error'=>null,
        			'emailerror'=>$e,
          		'success'=>true
        	));
        
        }
        
        echo json_encode(array(
            'error'=>null,
            'url'=>$url,
            'username'=>$_SESSION['user']['public']['email']
        ));
      }
      die();
    
    case 'login':
	    	$user_obj = new UserClass();	
	    	 $res = $user_obj->Authenticate($_POST["email"], $_POST["pass"]); 
	    	 if ( $res['error'] ) {
	    	 	echo json_encode(array(
	    	 			'error' => $res['error'],
	    	 			'url'=> null
	    	 	));
	    	} else {
	    		$_SESSION['user']['public'] = $res;
	    		$cart_obj = new cart();
	    		$cart_obj->SetUserCart($res['id']);
	    		$url = $_SERVER['HTTP_REFERER'];
	    		if ($_POST['redirect']) {
	    			$url = $_POST['redirect'];
	    		}
          if(empty($_SESSION['address'])){
          	$addressArr = $user_obj->GetUsersAddresses($res['id']);
            $_SESSION['address'] =  array("B"=> $addressArr[0], "same_address" => true);
          }
          
          
				echo json_encode(array(
						'error' => null,
						'url'=> $url,
          	'success'=>true
				));
	    	}
	    	die();
	    	
    case 'resetPasswordToken':
      $user_obj = new UserClass();
      $res = $user_obj->ResetPasswordToken($_POST["email"]);
      if($res['success']){
      	try{
      		$SMARTY->assign("user_gname",$res['user_gname']);
      		$SMARTY->assign("token",$res['token']);
      		$SMARTY->assign("email",$_POST["email"]);
      		$SMARTY->assign('DOMAIN', "http://" . $HTTP_HOST);
      		$COMP = json_encode($CONFIG->company);
      		$SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
      		$body= $SMARTY->fetch('email/reset-password.tpl');
      		$to = $_POST["email"];
      		$from = (string) $CONFIG->company->name;
      		$fromEmail = (string) $CONFIG->company->email_from;
      		$fromEmail = 'noreply@' . str_replace ( "www.", "", $GLOBALS['HTTP_HOST'] );
      		$subject = "{$from} | Password Recovery";
      		if(sendMail($to, $from, $fromEmail, $subject, $body)){
      			$success = $res['success'];
      		}else{
      			$error = 'Error while sending email. Please, try again later!';
      		}
      	}catch(Exception $e){
      		$error = $e;
      	}
      }else{
      	$error = $res['error'];
      }
      echo json_encode(array(
          'error'=>$res['error'],
          'success'=>$res['success']
      ));
      die();
      
    case 'passwordreset':
    	$user_obj = new UserClass();
      $res = $user_obj->ResetPassword($_POST["email"], $_POST['userToken'], $_POST['pass']);
      if(empty($res['error'])){
      	$_SESSION['user']['public'] = $res;
      	$url = $_SERVER['HTTP_REFERER'];
      	if ($_POST['redirect']) {
      		$url = $_POST['redirect'];
      	}
        echo json_encode(array(
            'error'=>false,
            'success'=>true,
            'url'=>$url
        ));
        die();
      }
      echo json_encode(array(
          'error'=>$error,
          'success'=>false,
          'url'=>$url
      ));
      die();
    
    case 'updatePassword':
      $user_obj = new UserClass();
      $data = array_merge($_POST,array(
          'email'=>$_SESSION['user']['public']['email']
      ));
      $res = $user_obj->UpdatePassword($data);
      if($res['error']){
        $_SESSION['error'] = $res['error'];
        header("Location: " . $_SERVER['HTTP_REFERER'] . "#error");
      }else{
        $_SESSION['notice'] = $res['success'];
        header("Location: " . $_SERVER['HTTP_REFERER'] . "#notice");
      }
      die();
    
		case 'unsubscribe':
	    if(!empty($_REQUEST['tk']) && !empty($_REQUEST['tl'])){
	    	$user_obj = new UserClass();
	     	$email = $user_obj->UnsubscribeUser($_REQUEST['tk'], $_REQUEST['tl']);
	     	if($email){
	     		SetMemberCampaignMonitor($LIST_ID, array('email'=>$email), 0);
	     		$_SESSION['notice'] = 'You have been successfully unsubscribed.';
	     		header("Location: " . $_SERVER['HTTP_REFERER'] . "#notice");
	     		die();
	     	}
     	}
     	$_SESSION['error'] = 'Something went wrong, please check your email and try again!';
     	header("Location: " . $_SERVER['HTTP_REFERER'] . "#error");
     	die();
      	
    case 'updateDetails':
    		$user_obj = new UserClass();
    		$data = array_merge($_POST, array('user_id'=>$_SESSION['user']['public']['id']));
    		$promo = 0;
    		$data['user_want_promo'] = empty($_POST['user_want_promo']) ? 0 : 1;
    		SetMemberCampaignMonitor($LIST_ID, $data, $data['user_want_promo']); 
    		
    		$res = $user_obj->UpdateDetails($data);
    		if($user_obj->InsertNewAddress(array_merge(
    				array(
	    				'address_user_id' => $_SESSION['user']['public']['id'], 
	    				'address_name' => $_POST['user_gname'],
    					'address_surname' => $_POST['user_surname']),
    				$_POST)	)){
    			$addressArr = $user_obj->GetUsersAddresses($_SESSION['user']['public']['id']);
    			$_SESSION['address'] =  array("S"=> $addressArr[0], "same_address" => true);
    		}
    		
    		if(empty($res['error'])){
    			$_SESSION['user']['public'] = $res['user_record'];
    			$_SESSION['notice']= $res['success'];
    			header("Location: ".$_SERVER['HTTP_REFERER']."#notice");
    		}else{
    			$_SESSION['error']= $res['error'];
    			header("Location: ".$_SERVER['HTTP_REFERER']."#error");
    		}
    		die();
    		
    		

  }
} 
$redirect = $_SERVER['HTTP_REFERER'];
if($_GET["logout"]){
	unset($_SESSION['user']['public']);
  unset($_SESSION['address']);
  unset($_SESSION['comments']);
  unset($_SESSION['agreed_tc']);
  session_regenerate_id();
  if(empty($redirect) || preg_match('/process/', $_SERVER['HTTP_REFERER'])) $redirect = '/';
  header('Location: ' . $redirect);
  die();
}
$_SESSION['error'] = 'Your session has expired.';
if(empty($redirect) || preg_match('/process/', $_SERVER['HTTP_REFERER'])) $redirect = '/';
header('Location: ' . $redirect .'#error');
die(); 
   



 
function SetMemberCampaignMonitor($listId, $data, $flag){
	global $CONFIG;
	
	if(empty($data) || empty($listId)){
		return false;
	}
	
	$customFields = array();
	$skipArr = array('email', 'gname', 'surname');
	foreach($data as $k => $v){
		if(!in_array($k, $skipArr)){
			$customFields[] = array('Key' => $k, 'Value' => $v);
		}
	}
	
	try{
		require_once 'includes/createsend/csrest_subscribers.php';
		$wrap = new CS_REST_Subscribers($listId, '060d24d9003a77b06b95e7c47691975b'); 
		if(empty($flag)){
			$cs_result = $wrap->unsubscribe($data['email']);
		}else{
			$cs_result = $wrap->add(array(
					'EmailAddress' => $data['email'],
					'Name' => $data['gname'] . ' ' . $data['surname'],
					'CustomFields' => $customFields,
					"Resubscribe" => "true"
			));
		}
		if($cs_result->was_successful()){
			return true;
		}
	}catch(Exception $e){
		$COMP = json_encode($CONFIG->company);
		$body= "Error: {$e}<br> Session: " . print_r($_SESSION, true);
		$to = 'apolo@them.com.au';
		$from = (string) $CONFIG->company->name;
		$fromEmail = (string) $CONFIG->company->email_from;
		$subject = "{$from} | Campaign monitor error";
		sendMail($to, $from, $fromEmail, $subject, $body);
	}
	return false;
}

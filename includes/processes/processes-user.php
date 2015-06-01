<?php

if(checkToken('frontend',$_POST["formToken"],false)){
  switch($_POST["action"]){
    case 'create':
      $user_obj = new UserClass();
      $_POST['username'] = $_POST['email'];
      $res = $user_obj->Create($_POST);
      if($res['error']){
        echo json_encode(array(
            'error'=>$res['error'],
            'url'=>null
        ));
      }else{
        $cart_obj = new cart();
        $message = $cart_obj->SetUserCart($res['id']);
        $_SESSION['user']['public'] = $res;
        $url = $_SERVER['HTTP_REFERER'];
        if($_POST['redirect']){
          $url = unclean($_POST['redirect']);
        }
        
        try{
        	// SEND CONFIRMATION EMAIL
        	$SMARTY->assign("DOMAIN",'http://'.$HTTP_HOST);
        	$COMP = json_encode($CONFIG->company);
        	$SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
        	$SMARTY->assign("username",$_POST['email']);
        	$SMARTY->assign("password",$_POST['password']);
        	
        	$buffer= $SMARTY->fetch('newmember-email.tpl');
        	$to = $_SESSION['user']['public']['email'];
        	$from = (string) $CONFIG->company->name;
        	$fromEmail = "noreply@" . str_replace ( "www.", "", $HTTP_HOST );
        	$subject = 'Your new account details';
        	$body = $buffer;
        	$mailID = sendMail($to, $from, $fromEmail, $subject, $body);
        	
        }catch(Exception $e){
        	echo json_encode(array(
        			'error'=>null,
        			'emailerror'=>$e
        	));
        
        }
        $_SESSION['notice'] = 'Your account has been successfully created';
        echo json_encode(array(
            'error'=>null,
            'url'=>$url,
            'username'=>$_SESSION['user']['public']['email'],
						'message'=> $message
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
	    		$cart_obj = new cart();
	    		$message = $cart_obj->SetUserCart($res['id']);
	    		$_SESSION['user']['public'] = $res;
	    		$url = $_SERVER['HTTP_REFERER'];
	    		if ($_POST['redirect']) {
                	$url = unclean($_POST['redirect']);
          }
          if(empty($_SESSION['address'])){
          	$addressArr = $user_obj->GetUsersAddresses($res['id']);
          	$_SESSION['address'] =  array("S"=> $addressArr[0], "same_address" => true);
          }
				echo json_encode(array(
						'error' => null,
						'url'=> $url,
						'message'=> $message
				));
	    	}
	    	die();
    
    case 'resetPassword':
      $user_obj = new UserClass();
      $res = $user_obj->resetPassword($_POST["email"]);
      if($res['success']){
      	try{
      		// SEND CONFIRMATION EMAIL
      		$SMARTY->assign("user_gname",$res['user_gname']);
      		$SMARTY->assign("newPass",$res['temp_pass']);
      		$SMARTY->assign('DOMAIN', "http://" . $HTTP_HOST);
      		$COMP = json_encode($CONFIG->company);
      		$SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
      		$body= $SMARTY->fetch('email-reset-password.tpl');
      		$to = $_POST["email"];
      		$from = (string) $CONFIG->company->name;
      		$fromEmail = "noreply@" . str_replace ( "www.", "", $HTTP_HOST );
      		$subject = 'Forgotten Password for '. $from;
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
          'error'=>$error,
          'success'=>$success
      ));
      die();
    case 'passwordreset':
      $status = false;
      $error="";
      $em = $_POST['email'];
      $tk = $_POST['userToken'];
      $pw = $_POST['pass'];
      $token = getPass($em,$tk);
    
      // SEND CONFIRMATION EMAIL
      $sql = "SELECT user_id,IF(user_token_date>=DATE_SUB(NOW( ),INTERVAL 4 HOUR),0,1) AS expired FROM tbl_user WHERE user_token = :token"; //Reset password
      if($res2 = $DBobject->wrappedSql ( $sql, array(':token' => $token)) ){
        if($res2[0]['expired'] == 1){
          $error = 'This url has expired, please request a new reset password.';
        }else{
          $temp_str = getPass($em,$pw);
          $params = array (
              ":id" => $res2[0]['user_id'],
              ":password" => $temp_str,
              ":ip" => $_SERVER['REMOTE_ADDR'],
              ":browser" => $_SERVER['HTTP_USER_AGENT']
          );
          $sql = "UPDATE tbl_user SET user_password = :password, user_ip = :ip,user_browser = :browser,user_modified = now() WHERE user_id = :id ";
          if ( $DBobject->wrappedSql($sql, $params) ) {
            $sql = "UPDATE tbl_user SET user_token = NULL, user_token_date = NULL WHERE user_id = :id "; //Reset password
            $DBobject->wrappedSql($sql, array("id"=>$res2[0]['user_id']));
            saveInLog('Edit', 'tbl_user', $res['id']);
            $status = true;
          }else{
            $error = 'There was a connection problem. Please, try again!';
          }
          try {
            $user_obj = new Member();
            $res = $user_obj->Authenticate($em, $pw);
            $cart_obj = new cart();
  	    		$message = $cart_obj->SetUserCart($res['id']);
  	    		$_SESSION['user']['public'] = $res;
  	    		$url = $_SERVER['HTTP_REFERER'];
  	    		if ($_POST['redirect']) {
                  	$url = unclean($_POST['redirect']);
            }
            if(empty($_SESSION['address'])){
            	$addressArr = $user_obj->GetUsersAddresses($res['id']);
            	$_SESSION['address'] =  array("S"=> $addressArr[0], "same_address" => true);
            }
            if(!empty($payments)) {
              $_SESSION['agreed_tc'] = true;
            }
          }catch(Exception $e){
      
          }
        }
      }else{
        $error = 'There was a connection problem. Please, try again!';
      }
    
      if($status){
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
    
    case 'updateDetails':
    	require_once 'includes/createsend/csrest_subscribers.php';
    	$user_obj = new UserClass();
    	/* if($_POST['want_promo']){
    		$_POST['want_promo'] = 1;
    		//============= ADD - CREATE-SEND
    		try{
    			$wrap = new CS_REST_Subscribers('', '060d24d9003a77b06b95e7c47691975b');
    			$cs_result = $wrap->add(array(
    					'EmailAddress' => $_SESSION['user']['public']['email'],
    					'Name' => $_POST['gname'],
    					'CustomFields' => array(
    							array(
    									'Key' => 'Surname ',
    									'Value' => $_POST['surname '],
    							)
    					),
    					"Resubscribe" => "true"
    			));
    		}catch(Exception $e){}
    	}else{
    		//============= REMOVE - CREATE-SEND
    		try{
    			$wrap = new CS_REST_Subscribers('', '060d24d9003a77b06b95e7c47691975b');
    			$cs_result = $wrap->unsubscribe($_SESSION['user']['public']['email']);
    		}catch(Exception $e){}
    		 
    	} */
    	
    	$data = array_merge($_POST, array('email'=>$_SESSION['user']['public']['email']));
    	$res = $user_obj->UpdateDetails($data);
    	if ( $res['error'] ) {
    		$_SESSION['error']= $res['error'];
    		header("Location: ".$_SERVER['HTTP_REFERER']."#error");
    	} else {
    		$_SESSION['user']['public']['gname']= $_POST["gname"];
    		$_SESSION['user']['public']['surname']= $_POST["surname"];
    		$_SESSION['notice']= $res['success'];
    		header("Location: ".$_SERVER['HTTP_REFERER']."#notice");
    	}
    	die();

  }
}elseif($_GET["logout"]){
	unset($_SESSION['user']['public']);
  unset($_SESSION['address']);
  session_regenerate_id();
  $redirect = $_SERVER['HTTP_REFERER'];
  if(empty($redirect) || preg_match('/process/', $_SERVER['HTTP_REFERER'])) $redirect = '/';
  header('Location: ' . $redirect);
  die();
}
	
header("Location: /404");
die();
  

 
  
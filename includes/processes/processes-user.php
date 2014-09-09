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
        $cart_obj = new Enrolment();
        $message = $cart_obj->SetUserCart($res['id']);
        $_SESSION['user']['public'] = $res;
        $url = $_SERVER['HTTP_REFERER'];
        if($_POST['redirect']){
          $url = $_POST['redirect'];
        }
        
        try{
        	// SEND CONFIRMATION EMAIL
        	$SMARTY->assign("domainName",'http://'.$GLOBALS['HTTP_HOST']);
        	$SMARTY->assign("username",$_POST['email']);
        	$SMARTY->assign("password",$_POST['password']);
        	
        	$buffer= $SMARTY->fetch('newmember-email.tpl');
        	$to = $_SESSION['user']['public']['email'];
        	$from = 'Website';
        	$fromEmail = "noreply@" . str_replace ( "www.", "", $_SERVER ['HTTP_HOST'] );
        	$subject = 'Your new account details';
        	$body = $buffer;
        	$mailID = sendMail($to, $from, $fromEmail, $subject, $body);
        	
        }catch(Exception $e){
        	echo json_encode(array(
        			'error'=>null,
        			'emailerror'=>$e
        	));
        
        }
        
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
	    		$cart_obj = new Enrolment();
	    		$message = $cart_obj->SetUserCart($res['id']);
	    		$_SESSION['user']['public'] = $res;
	    		$url = $_SERVER['HTTP_REFERER'];
	    		if ($_POST['redirect']) {
                	$url = $_POST['redirect'];
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
      echo json_encode(array(
          'error'=>$res['error'],
          'success'=>$res['success']
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
    		$user_obj = new UserClass();
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
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  die();
}
	
header("Location: /404");
die();
  

 
  
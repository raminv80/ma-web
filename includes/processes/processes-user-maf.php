<?php
if(!empty($_POST["formToken"]) && checkToken('frontend', $_POST["formToken"], false)){
  
  /**
   * ************ SET CAMPAIGN MONITOR LIST ID WHEN NEEDED ********
   */
  $LIST_ID = '';
  /**
   * ************ **************************************** ********
   */
  
  switch($_POST["action"]){
    case 'createTemporaryMember':
      $error = "Error: Missing parameters.";
      $success = null;
      $url = null;
      $_SESSION['user']['new_user'] = '';
      if(!empty($_POST['gname']) && !empty($_POST['surname']) && !empty($_POST['dob']) && !empty($_POST['gender']) 
          && !empty($_POST['address']) && !empty($_POST['suburb']) && !empty($_POST['state']) && !empty($_POST['postcode']) && !empty($_POST['mobile']) && !empty($_POST['email']) && !empty($_POST['password'])){
        
        $error = "Error: Invalid date of birth (DD/MM/YYYY).";
        if(validateDate($_POST['dob'])){
          $excludeArr = array('formToken', 'action', 'redirect');
          foreach($_POST as $k => $v){
            if(!in_array($k, $excludeArr)){
              $_SESSION['user']['new_user'][$k] = $v;
            }
          }
          $_SESSION['user']['new_user']['db_dob'] = date_format(date_create_from_format('d/m/Y', $_SESSION['user']['new_user']['dob']), 'Y-m-d');
          $error = null;
          $success = true;
          $url = empty($_POST['redirect']) ? '/checkout' : $_POST['redirect'];
        }
      }
      echo json_encode(array(
          'error' => $error,
          'url' => $url,
          'success' => $success
      ));
      die();
      
    case 'create': //NOT BEING USED!!!
      $_POST['want_promo'] = empty($_POST['want_promo'])? 0 : 1;
      SetMemberCampaignMonitor($LIST_ID, $_POST, $_POST['want_promo']);
      
      $user_obj = new UserClass();
      $_POST['username'] = $_POST['email'];
      $res = $user_obj->Create($_POST);
      if($res['error']){
        echo json_encode(array(
            'error' => $res['error'], 
            'url' => null 
        ));
      } else{
        $_SESSION['user']['public'] = $res;
        $cart_obj = new cart($_SESSION['user']['public']['id']);
        $url = $_SERVER['HTTP_REFERER'];
        if($_POST['redirect']){
          $url = $_POST['redirect'];
        }
        
        try{
          // SEND CONFIRMATION EMAIL
          $SMARTY->assign("DOMAIN", 'http://' . $HTTP_HOST);
          $COMP = json_encode($CONFIG->company);
          $SMARTY->assign('COMPANY', json_decode($COMP, TRUE));
          $SMARTY->assign("name", $res['gname']);
          $SMARTY->assign("username", $_POST['email']);
          $SMARTY->assign("password", $_POST['password']);
          
          $buffer = $SMARTY->fetch('email/welcome.tpl');
          $to = $_SESSION['user']['public']['email'];
          $from = (string)$CONFIG->company->name;
          $fromEmail = "noreply@" . str_replace("www.", "", $HTTP_HOST);
          $subject = "{$from} | New Membership";
          $body = $buffer;
          $mailID = sendMail($to, $from, $fromEmail, $subject, $body, null, $res['id']);
        }
        catch(Exception $e){
          echo json_encode(array(
              'error' => null, 
              'emailerror' => $e, 
              'success' => true 
          ));
        }
        
        echo json_encode(array(
            'error' => null, 
            'url' => $url, 
            'username' => $_SESSION['user']['public']['email'] 
        ));
      }
      die();
    
    case 'login':
      $error = "Error: Missing parameters.";
      $success = null;
      $url = null;
      if(!empty($_POST['username']) && !empty($_POST['pass'])){
        $user_obj = new UserClass();
        if($user_obj->authenticate($_POST['username'], $_POST['pass'])){
          $error = "Error: no data.";
          if($_SESSION['user']['public']['maf'] = $user_obj->getSessionVars()){
            $_SESSION['user']['public']['id'] = $_SESSION['user']['public']['maf']['main']['user_id'];
            $_SESSION['user']['public']['gname'] = $_SESSION['user']['public']['maf']['main']['user_firstname'];
            $_SESSION['user']['public']['surname'] = $_SESSION['user']['public']['maf']['main']['user_lastname'];
            $_SESSION['user']['public']['email'] = $_SESSION['user']['public']['maf']['main']['user_email'];
            $error = null;
            $success = true;
            $url = empty($_POST['redirect']) ? $_SERVER['HTTP_REFERER'] : $_POST['redirect'];
            saveInLog('member-login', 'external', $_SESSION['user']['public']['id']);
          }
        }else{
          $error = $user_obj->getErrorMsg();
        }
      }
      echo json_encode(array(
          'error' => $error,
          'url' => $url,
          'success' => $success
      ));
      die();
      
    
    case 'resetPasswordToken':
      $member = new UserClass();
      $success = null;
      $error = 'Undefined error. Please <a href="/contact-us">contact us</a>.';
      try{
        $authenticationRecord = json_decode($member->medicAlertApi->authenticate(medicAlertApi::API_USER, medicAlertApi::API_USER_PASSWORD), true);
      }catch(exceptionMedicAlertApiNotAuthenticated $e){
        $_SESSION['user']['public'] = null;
        $error = "API init error: {$e}";
      }catch(exceptionMedicAlertApiSessionExpired $e){
        $_SESSION['user']['public'] = null;
        $error = "API init error: {$e}";
      }catch(exceptionMedicAlertApi $e){
        $error = "API init error: {$e}";
      }
      $sessionToken = $authenticationRecord['sessionToken'];
      try{
        $results = $member->medicAlertApi->lostPassWord($sessionToken, $_POST["membership_number"], $_POST["email"]);
        $success = "An email has been sent to your";
      }catch(exceptionMedicAlertNotFound $e){
        $error = 'The details you entered do not match any member record.';
      }
      	
      // logout of the API
      try{
        $member->medicAlertApi->logout($sessionToken);
      }catch(exceptionMedicAlertApiNotAuthenticated $e){
        $_SESSION['user']['public'] = null;
        $error = "API error: {$e}";
      }catch(exceptionMedicAlertApiSessionExpired $e){
        $_SESSION['user']['public'] = null;
        $error = "API error: {$e}";
      }catch(exceptionMedicAlertApi $e){
        $error = "API error: {$e}";
      }
      
      echo json_encode(array(
          'error' => $error, 
          'success' => $success
      ));
      die();
    
    case 'passwordreset':
      
      $user_obj = new UserClass();
      $res = $user_obj->ResetPassword($_POST["email"], $_POST['userToken'], $_POST['pass']);
      if(empty($res['error'])){
        $_SESSION['user']['public'] = $res;
        $url = $_SERVER['HTTP_REFERER'];
        if($_POST['redirect']){
          $url = $_POST['redirect'];
        }
        echo json_encode(array(
            'error' => false, 
            'success' => true, 
            'url' => $url 
        ));
        die();
      }
      echo json_encode(array(
          'error' => $error, 
          'success' => false, 
          'url' => $url 
      ));
      die();
    
    case 'updatePassword':
      $error = "Your session has expired.";
      $success = null;
      $refresh = true;
      if(!empty($_SESSION['user']['public']['maf'])){
        $refresh = null;
        $error = "Error: Missing parameters.";
        if(!empty($_POST['current']) && !empty($_POST['new'])){
          try {
            $error = "Incorrect current password.";
            $user_obj = new UserClass();
            if($user_obj->UpdatePassword($_SESSION['user']['public']['maf']['token'], $_POST['current'], $_POST['new'])){
              //Password updated
              saveInLog('password-update', 'external', $_SESSION['user']['public']['id']);
              $memberId = $_SESSION['user']['public']['id'];
              //Log out
              $user_obj->logOut($_SESSION['user']['public']['maf']['token']);
              $_SESSION['user']['public'] = null;
              //Login with new password
              if($user_obj->authenticate($memberId, $_POST['new'])){
                $error = "Error: no data.";
                if($_SESSION['user']['public']['maf'] = $user_obj->getSessionVars()){
                  $_SESSION['user']['public']['id'] = $_SESSION['user']['public']['maf']['main']['user_id'];
                  $_SESSION['user']['public']['gname'] = $_SESSION['user']['public']['maf']['main']['user_firstname'];
                  $_SESSION['user']['public']['surname'] = $_SESSION['user']['public']['maf']['main']['user_lastname'];
                  $_SESSION['user']['public']['email'] = $_SESSION['user']['public']['maf']['main']['user_email'];
                  $error = null;
                  $success = 'Your password has been updated.';
                }
              }else{
                $error = $user_obj->getErrorMsg();
              }
            }else{
              $error = $user_obj->getErrorMsg();
            }
          }catch(Exception $e){
            $error = $e;
          }
        }
      }
      if(!empty($error) && preg_match('/API error/', $error)){
        $_SESSION['user']['public'] = null;
        $error = 'Your session has expired.<br>This page will be reloaded in 5 secs.';
        $refresh = true;
      }
      echo json_encode(array(
          'error' => $error,
          'refresh' => $refresh,
          'success' => $success
      ));
      die();
    
    case 'unsubscribe':
      if(!empty($_REQUEST['tk']) && !empty($_REQUEST['tl'])){
        $user_obj = new UserClass();
        $email = $user_obj->UnsubscribeUser($_REQUEST['tk'], $_REQUEST['tl']);
        if($email){
          SetMemberCampaignMonitor($LIST_ID, array(
              'email' => $email 
          ), 0);
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
      $data = array_merge($_POST, array(
          'user_id' => $_SESSION['user']['public']['id'] 
      ));
      $promo = 0;
      $data['user_want_promo'] = empty($_POST['user_want_promo'])? 0 : 1;
      SetMemberCampaignMonitor($LIST_ID, $data, $data['user_want_promo']);
      
      $res = $user_obj->UpdateDetails($data);
      if($user_obj->InsertNewAddress(array_merge(array(
          'address_user_id' => $_SESSION['user']['public']['id'], 
          'address_name' => $_POST['user_gname'], 
          'address_surname' => $_POST['user_surname'] 
      ), $_POST))){
        $addressArr = $user_obj->GetUsersAddresses($_SESSION['user']['public']['id']);
        $_SESSION['user']['public']['address'] = array(
            "S" => $addressArr[0], 
            "same_address" => true 
        );
      }
      
      if(empty($res['error'])){
        $_SESSION['user']['public'] = $res['user_record'];
        $_SESSION['notice'] = $res['success'];
        header("Location: " . $_SERVER['HTTP_REFERER'] . "#notice");
      } else{
        $_SESSION['error'] = $res['error'];
        header("Location: " . $_SERVER['HTTP_REFERER'] . "#error");
      }
      die();
  }
}
$redirect = $_SERVER['HTTP_REFERER'];
if($_GET["logout"]){
  $user_obj = new UserClass();
  saveInLog('member-logout', 'external', $_SESSION['user']['public']['id'], $_SESSION['user']['public']['maf']['token']);
  $user_obj->logOut($_SESSION['user']['public']['maf']['token']);
  $_SESSION['user']['public'] = null;
  session_regenerate_id();
  if(empty($redirect) || preg_match('/process/', $_SERVER['HTTP_REFERER'])) $redirect = '/';
  header('Location: ' . $redirect);
  die();
}
if($_GET["logout-maf"]){
  $user_obj = new UserClass();
  $user_obj->logOut($_SESSION['user']['public']['maf']['token']);
  if(empty($redirect) || preg_match('/process/', $_SERVER['HTTP_REFERER'])) $redirect = '/';
  header('Location: ' . $redirect);
  die();
}
$_SESSION['error'] = 'Your session has expired.';
if(empty($redirect) || preg_match('/process/', $_SERVER['HTTP_REFERER'])) $redirect = '/';
header('Location: ' . $redirect . '#error');
die();


function SetMemberCampaignMonitor($listId, $data, $flag){
  global $CONFIG;
  
  if(empty($data) || empty($listId)){
    return false;
  }
  
  $customFields = array();
  $skipArr = array(
      'email', 
      'gname', 
      'surname' 
  );
  foreach($data as $k => $v){
    if(!in_array($k, $skipArr)){
      $customFields[] = array(
          'Key' => $k, 
          'Value' => $v 
      );
    }
  }
  
  try{
    require_once 'includes/createsend/csrest_subscribers.php';
    $wrap = new CS_REST_Subscribers($listId, '060d24d9003a77b06b95e7c47691975b');
    if(empty($flag)){
      $cs_result = $wrap->unsubscribe($data['email']);
    } else{
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
  }
  catch(Exception $e){
    $COMP = json_encode($CONFIG->company);
    $body = "Error: {$e}<br> Session: " . print_r($_SESSION, true);
    $to = 'apolo@them.com.au';
    $from = (string)$CONFIG->company->name;
    $fromEmail = (string)$CONFIG->company->email_from;
    $subject = "{$from} | Campaign monitor error";
    sendMail($to, $from, $fromEmail, $subject, $body);
  }
  return false;
}

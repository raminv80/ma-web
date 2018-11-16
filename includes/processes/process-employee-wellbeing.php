<?php
$error = 'Missing required info. Please try again.';
if(checkToken('frontend', $_POST["formToken"]) && empty($_POST['honeypot']) && (time() - $_POST['timestamp']) > 3){
  if(!empty($_POST['email']) && !empty($_POST['name']) && !empty($_POST['company']) && !empty($_POST['phone']) && is_numeric($_POST['phone'])){
    global $CONFIG, $DBobject, $SMARTY, $SITE, $GA_ID;
  
    $error = '';
    $sent = 0;
    $file_name = '';
    
    // SEND EMAIL TO ADMIN
    try{
      $SMARTY->unloadFilter('output', 'trimwhitespace');
      
      $banned = array(
          'formToken', 
          'action', 
          'additional', 
          'wantpromo', 
          'enqsub', 
          'Hp', 
          'timestamp', 
          'honeypot' 
      );
      $content = serialize($_POST);
      $buf .= '<h2>Website ' . $_POST['form_name'] . '</h2>';
      foreach($_POST as $name => $var){
        if(!in_array($name, $banned)){
          $buf .= '<br/><b>' . ucwords(str_replace('_', ' ', $name)) . ': </b> <br/> ' . $var . '<br/>';
        }
      }
      $body = $buf;
      $subject = 'Website ' . $_POST['form_name'];
      $fromEmail = (string)$CONFIG->company->email_from;
      $to = getenv('EMAIL_EMPLOYEE_WELLBEING');
      $COMP = json_encode($CONFIG->company);
      $SMARTY->assign('COMPANY', json_decode($COMP, TRUE));
      $from = (string)$CONFIG->company->name;
      $bcc = null;

      $sent = sendMail($to, $from, $fromEmail, $subject, $body, $bcc);

    }
    catch(Exception $e){
      $error = 'There was an error sending your enquiry.';
    }
    
    // SAVE IN DATABASE
    if(empty($error)){
      try{
        $sql = "INSERT INTO tbl_employee_wellbeing (
                employee_wellbeing_name,employee_wellbeing_company,employee_wellbeing_email,employee_wellbeing_phone,
                employee_wellbeing_browser,employee_wellbeing_ip,employee_wellbeing_email_id,employee_wellbeing_created)
              VALUES (
                :employee_wellbeing_name,:employee_wellbeing_company,:employee_wellbeing_email,:employee_wellbeing_phone,
                :employee_wellbeing_browser,:employee_wellbeing_ip,:employee_wellbeing_email_id,now() )";
        
        $params = array(
            ":employee_wellbeing_name" => $_POST['name'], 
            ":employee_wellbeing_company" => $_POST['company'], 
            ":employee_wellbeing_email" => $_POST['email'], 
            ":employee_wellbeing_phone" => $_POST['phone'],  
            ":employee_wellbeing_browser" => $_SERVER['HTTP_USER_AGENT'],
            ":employee_wellbeing_ip" => $_SERVER['REMOTE_ADDR'], 
            ":employee_wellbeing_email_id" => $sent 
        );
        
        $DBobject->wrappedSql($sql, $params);
      }
      catch(Exception $e){
        $error = 'There was an unexpected error saving your enquiry.';
      }
    }
    
    if(empty($error)){
      /*
       * try{
       * $SMARTY->assign('DOMAIN', "http://" . $_SERVER['HTTP_HOST']);
       * $body = $SMARTY->fetch("email-thank-you.tpl");
       * $subject = 'Thank you for your enquiry';
       * $fromEmail = (string) $CONFIG->company->email_from;
       * $to = $_POST['email'];
       * $COMP = json_encode($CONFIG->company);
       * $SMARTY->assign('COMPANY', json_decode($COMP,TRUE));
       * $from = (string) $CONFIG->company->name;
       * $sent = sendMail($to, $from, $fromEmail, $subject, $body);
       * }catch (Exception $e){
       * $error = 'There was an error sending the employee_wellbeing email.';
       * }
       */
      
      if(!empty($GA_ID)){
        sendGAEvent($GA_ID, 'Enquiry', 'Submitted', $_POST['form_name']);
      }
      
      header("Location: /thank-you");
      die();
    }
  } else{
    $error = 'Please provide the required information.';
  }
} else{
  $error = 'Your session has expired.<br>Please try again.';
}

$_SESSION['post'] = $_POST;
$_SESSION['error'] = $error;
header("Location: {$_SERVER['HTTP_REFERER']}#form-error");
die();


function SetMemberCampaignMonitor($listId, $data, $flag = 1){
  global $CONFIG;
  
  if(empty($data) || empty($listId)){
    return false;
  }
  
  $customFields = array();
  if(key_exists('customFields', $data)){
    foreach($data['customFields'] as $k => $v){
      if(!in_array($k, $skipArr)){
        $customFields[] = array(
            'Key' => $k,
            'Value' => $v
        );
      }
    }
    unset($data['customFields']);
  }
  
  try{
    require_once 'includes/createsend/csrest_subscribers.php';
    $wrap = new CS_REST_Subscribers($listId, '060d24d9003a77b06b95e7c47691975b');
    if(empty($flag)){
      $cs_result = $wrap->unsubscribe($data['email']);
    } else{
      $cs_result = $wrap->add(array(
          'EmailAddress' => $data['email'],
          'Name' => $data['fname'] . ' ' . $data['lname'],
          'CustomFields' => $customFields,
          "Resubscribe" => "true"
      ));
    }
    //die(var_dump($cs_result));
    if($cs_result->was_successful()){
      return true;
    }
  }
  catch(Exception $e){
    $COMP = json_encode($CONFIG->company);
    $body = "Error: {$e}<br> Session: " . print_r($_SESSION, true);
    $to = getenv('EMAIL_APP_SUPPORT');
    $from = (string)$CONFIG->company->name;
    $fromEmail = (string)$CONFIG->company->email_from;
    $subject = "{$from} | Campaign monitor error";
    sendMail($to, $from, $fromEmail, $subject, $body);
  }
  return false;
}




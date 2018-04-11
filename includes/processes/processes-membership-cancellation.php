<?php
$error = 'Missing required info. Please try again.';
if(checkToken('frontend', $_POST["formToken"]) && empty($_POST['honeypot']) && (time() - $_POST['timestamp']) > 3){
  if(!empty($_POST['name']) && !empty($_POST['membership_no']) && is_numeric($_POST['membership_no'])){
    global $CONFIG, $DBobject, $SMARTY, $SITE, $GA_ID;
    
    $error = '';
    $sent = 0;
    
    // SEND EMAIL TO ADMIN
    if(empty($error)){
      try{
        $SMARTY->unloadFilter('output', 'trimwhitespace');
        
        if(isset($_POST['requested_for_someone'])){
          $banned = array(
              'formToken', 
              'action', 
              'additional', 
              'wantpromo', 
              'enqsub', 
              'Hp', 
              'timestamp', 
              'honeypot',
              'form_name',
              'acknowledgement'
          );
        } else{
          $banned = array(
              'formToken',
              'action',
              'additional',
              'wantpromo',
              'enqsub',
              'Hp',
              'timestamp',
              'honeypot',
              'form_name',
              'acknowledgement',
              'requested_by',
              'requested_by_phone',
              'relation_to_member',
              'other_relation'
          );
        }
        $content = serialize($_POST);
        $buf .= '<h2>Website ' . $_POST['form_name'] . '</h2>';
        foreach($_POST as $name => $var){
          if(!in_array($name, $banned)){
            if($name == 'other_reason' || $name == 'other_relation'){
              if($var != ''){
                $buf .= '<br/><b>' . ucwords(str_replace('_', ' ', $name)) . ': </b> <br/> ' . $var . '<br/>';
              }
            } else{
              $buf .= '<br/><b>' . ucwords(str_replace('_', ' ', $name)) . ': </b> <br/> ' . $var . '<br/>';
            }
          }
        }
        $body = $buf;
        $subject = 'Website ' . $_POST['form_name'];
        $fromEmail = (string)$CONFIG->company->email_from;
        $to = (string)$CONFIG->company->email_contact;
        $COMP = json_encode($CONFIG->company);
        $SMARTY->assign('COMPANY', json_decode($COMP, TRUE));
        $from = (string)$CONFIG->company->name;
        $bcc = null;
        if(!empty($file_name)){
          if(sendAttachMail($to, $from, $fromEmail, $subject, $body, $bcc, $file_name)){
            $sent = 1;
          }
        } else{
          $sent = sendMail($to, $from, $fromEmail, $subject, $body, $bcc);
        }
      }
      catch(Exception $e){
        $error = 'There was an error sending your request.';
      }
    }
    
    // SAVE IN DATABASE
    if(empty($error)){
      try{
        $sql = "INSERT INTO tbl_member_cancellation_request (member_cancellation_request_name, member_cancellation_request_email, 
                member_cancellation_request_phone, member_cancellation_request_membership_number, member_cancellation_request_reason,  
                member_cancellation_request_requested_by, member_cancellation_request_requested_by_phone, member_cancellation_request_relation_to_member, 
                member_cancellation_request_ip, member_cancellation_request_browser, member_cancellation_request_created)
                VALUES (:member_name, :member_email, :member_phone, :member_membership_number, :member_reason,
                :requested_by_name, :requested_by_phone, :relation_to_member, :member_ip, :member_browser, now() )";
        $params = array(
            ":member_name" => $_POST['name'], 
            ":member_email" => $_POST['email'], 
            ":member_phone" => $_POST['phone'], 
            ":member_membership_number" => $_POST['membership_no'],
            ":member_reason" => ($_POST['cancellation_reason'] == "Other")? $_POST['other_reason'] : $_POST['cancellation_reason'],
            ":requested_by_name" => $_POST['requested_by'],
            ":requested_by_phone" => $_POST['requested_by_phone'],
            ":relation_to_member" => ($_POST['relation_to_member'] == "Other")? $_POST['other_relation'] : $_POST['relation_to_member'],
            ":member_ip" => $_SERVER['REMOTE_ADDR'], 
            ":member_browser" => $_SERVER['HTTP_USER_AGENT']
        );
        $DBobject->wrappedSql($sql, $params);
      }
      catch(Exception $e){
        $error = 'There was an unexpected error saving your request.';
      }
    }
    
    if(empty($error)){
      header("Location: /request-received");
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
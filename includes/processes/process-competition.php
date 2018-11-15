<?php
die('disabled');
$error = 'Missing required info. Please try again.';
if(checkToken('frontend', $_POST["formToken"]) && empty($_POST['honeypot']) && (time() - $_POST['timestamp']) > 3){
  if(!empty($_POST['email']) && !empty($_POST['phone']) && !empty($_POST['name']) && !empty($_POST['entry']) && !empty($_POST['postcode']) && is_numeric($_POST['postcode'])){
    global $CONFIG, $DBobject, $SMARTY, $GA_ID;
    
    $error = '';
    $sent = 0;
    $file_name = '';
    
    $contact_email = "";
  
    
    
    // SEND EMAIL TO ADMIN
    if(empty($error)){
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
        if($contact_email){
          $to = $contact_email;
        }else{
          $to = (string)$CONFIG->company->email_contact;
        }
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
        $error = 'There was an error sending your enquiry.';
      }
    }
    
    // SAVE IN DATABASE
    if(empty($error)){
      try{
        $sql = "INSERT INTO tbl_competition (competition_form_name,competition_reference_id,competition_reference_name,competition_name,competition_email,
    		    competition_phone,competition_postcode,competition_membership_number, competition_entry,competition_content1,competition_content2,competition_flag1,competition_flag2,competition_heardabout, competition_ip,
    		    competition_email_id, competition_browser, competition_ga_clientid, competition_created)
              VALUES (:competition_form_name, :competition_reference_id, :competition_reference_name, :competition_name, :competition_email, :competition_phone,
    		    :competition_postcode, :competition_membership_number, :competition_entry, :competition_content1, :competition_content2, :competition_flag1, :competition_flag2, :competition_heardabout, :competition_ip,
    		    :competition_email_id, :competition_browser, :competition_ga_clientid, now() )";
        $params = array(
            ":competition_name" => $_POST['name'],
            ":competition_form_name" => $_POST['form_name'],
            ":competition_reference_id" => $_POST['competition_reference_id'],
            ":competition_reference_name" => $_POST['competition_reference_name']?$_POST['nature_enquiry']:'',
            ":competition_name" => $_POST['name'],
            ":competition_email" => $_POST['email'],
            ":competition_phone" => $_POST['phone'],
            ":competition_postcode" => $_POST['postcode'],
            ":competition_membership_number" => $_POST['membership_no'],
            ":competition_entry" => $_POST['entry'],
            ":competition_content1" => '',
            ":competition_content2" => '',
            ":competition_flag1" => '',
            ":competition_flag2" => '',
            ":competition_heardabout" => $_POST['heardabout'],
            ":competition_ip" => $_SERVER['REMOTE_ADDR'],
            ":competition_browser" => $_SERVER['HTTP_USER_AGENT'],
            ":competition_ga_clientid" => gaParseCookie(),
            ":competition_email_id" => $sent
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
       * $error = 'There was an error sending the contact email.';
       * }
       */
      
      if(!empty($GA_ID)){
        sendGAEvent($GA_ID, 'Competition Entry', 'Submitted', $_POST['form_name']);
      }
      
      
      if(strtolower($_POST['form_name']) == "competition"){
        header("Location: /thank-you-for-completing-our-survey");
        die();
      }
      
      header("Location: /thank-you-for-completing-our-survey");
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
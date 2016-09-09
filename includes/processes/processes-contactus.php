<?php
$error = 'Missing required info. Please try again.';
if(checkToken('frontend', $_POST["formToken"]) && empty($_POST['honeypot']) && (time() - $_POST['timestamp']) > 3){
  if(!empty($_POST['email']) && !empty($_POST['phone']) && !empty($_POST['name']) && !empty($_POST['enquiry']) && !empty($_POST['postcode']) && is_numeric($_POST['postcode'])){
    global $CONFIG, $DBobject, $SMARTY, $SITE, $GA_ID;
    
    $error = '';
    $sent = 0;
    $file_name = '';
    
    // SAVE FILE FUNCTIONS (Accepts Image types)
    /* try{
      if(!empty($_FILES["file"]["name"])){
        if($_FILES["file"]["error"] == 0){
          $acceptedFileFormat = array(
              'pdf', 
              'jpeg', 
              'jpg', 
              'gif', 
              'png' 
          );
          if(in_array(strtolower(substr($_FILES["file"]["name"], -3)), $acceptedFileFormat)){
            $path = $_SERVER['DOCUMENT_ROOT'];
            $file_short = time() . '_' . str_replace(" ", '', $_FILES["file"]["name"]);
            // Set directory
            $filedir = $path . '/uploads_contact/';
            if(!file_exists($filedir)){
              mkdir($filedir, 0755);
            }
            // Store file
            $_POST['filename'] = $file_short;
            $file_name = $filedir . $file_short;
            if(!move_uploaded_file($_FILES["file"]["tmp_name"], $file_name)){
              $error = 'Invalid file name. Please rename your file.';
            }
          } else{
            $error = 'Please check your file extension (use PDF, JPG , GIF or  PNG).';
          }
        } else{
          $error = 'Please check your file and  try again later.';
        }
      }
    } 
    catch(Exception $e){
      $error = 'Please check your file and  try again later.';
    }
    */
    
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
        $error = 'There was an error sending your enquiry.';
      }
    }
    
    // SAVE IN DATABASE
    if(empty($error)){
      try{
        $sql = "INSERT INTO tbl_contact (contact_site,contact_form_name,contact_reference_id,contact_reference_name,contact_name,contact_email,
    		    contact_phone,contact_postcode,contact_file,contact_enquiry,contact_content1,contact_content2,contact_flag1,contact_flag2,contact_ip,
    		    contact_email_id,contact_created)
              VALUES (:contact_site,:contact_form_name,:contact_reference_id,:contact_reference_name,:contact_name,:contact_email,:contact_phone,
    		    :contact_postcode,:contact_file,:contact_enquiry,:contact_content1,:contact_content2,:contact_flag1,:contact_flag2,:contact_ip,
    		    :contact_email_id,now() )";
        $params = array(
            ":contact_name" => $_POST['name'], 
            ":contact_site" => $SITE, 
            ":contact_form_name" => $_POST['form_name'], 
            ":contact_reference_id" => '', 
            ":contact_reference_name" => $_POST['nature_enquiry'], 
            ":contact_name" => $_POST['name'], 
            ":contact_email" => $_POST['email'], 
            ":contact_phone" => $_POST['phone'], 
            ":contact_postcode" => $_POST['postcode'], 
            ":contact_file" => empty($file_short)? "" : "/uploads_contact/" . $file_short, 
            ":contact_enquiry" => $_POST['enquiry'], 
            ":contact_content1" => $_POST['membership_no'], 
            ":contact_content2" => '', 
            ":contact_flag1" => '', 
            ":contact_flag2" => '', 
            ":contact_ip" => $_SERVER['REMOTE_ADDR'], 
            ":contact_email_id" => $sent 
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
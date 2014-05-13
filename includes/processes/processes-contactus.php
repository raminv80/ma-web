<?php

if(checkToken('frontend',$_POST["formToken"], false)){
  switch($_POST["action"]){
    case 'question':
      require_once 'includes/classes/mail/email-class.php';
      global $DBobject,$SMARTY,$SITE;
      $error = array();
      
      try{
        $sql = "SELECT listing_name, location_phone,location_enquiry_recipient,location_order_recipient,location_bcc_recipient FROM tbl_listing LEFT JOIN tbl_location ON listing_id = location_listing_id WHERE listing_object_id = :id AND listing_published = 1 AND listing_deleted IS NULL";
        $params = array(":id"=>$_POST['listing_id']);
        $res = $DBobject->wrappedSql($sql,$params);
        $SMARTY->assign("storename",$res[0]['listing_name']);
        $SMARTY->assign("storephone",$res[0]['location_phone']);
        $template= $SMARTY->fetch('thanks.tpl');
      }catch(Exception $e){
        $error = 'There was an error finding your chosen store.';
      } 
      
      //SAVE FILE FUNCTIONS (Accepts Image types)
      $file_name = "";
      try{
        if(!empty($_FILES["file"]["name"])){
          if($_FILES["file"]["error"] == 0){
            if(($_FILES["file"]["type"] == "application/pdf") || strtolower(substr($_FILES["file"]["name"],- 3)) == 'pdf' || strtolower(substr($_FILES["file"]["name"],- 3)) == 'jpg' || strtolower(substr($_FILES["file"]["name"],- 3)) == 'png' || strtolower(substr($_FILES["file"]["name"],- 3)) == 'gif'){
              $path = getcwd() . '/';
              $file_short = time() . '_' . str_replace(" ",'',$_FILES["file"]["name"]);
              $_POST['filename'] = $file_short;
              $file_name = $path . "uploads_contact/" . $file_short;
              if(move_uploaded_file($_FILES["file"]["tmp_name"],$file_name)){
              }
            }else{
              $error = 'Please check your file extension (use PDF, JPG , GIF or  PNG).';
            }
          }else{
            $error = 'Please check your file and  try again later.';
          }
        }
      }catch(Exception $e){
        $error = 'Please check your file and  try again later.';
      } 
      $sent = 0;
      try{
        $banned=array('formToken','action','redirect');
        //Send email
        $buf='<h2>Website contact - Contact Us form</h2><br/><br/>';
        foreach ($_POST as $name => $var){
           if(!in_array($name, $banned)){
            $buf.='<br/><b>'.$name.': </b> <br/> '.$var.'<br/>';
          }
        }
      
        $sendEmail = new email();
        $sendEmail->to = "cmsemails@them.com.au";//"belinda@them.com.au";
       /*  if(!empty($res[0]['location_enquiry_recipient'])){
          $sendEmail->to = $res[0]['location_enquiry_recipient'];
        }
        if(!empty($res[0]['location_bcc_recipient'])){
          $sendEmail->bcc = $res[0]['location_bcc_recipient'];
        } */
        $sendEmail->subject = 'Website contact - Contact Us form';
        $sendEmail->body = '<img src="http://'. $_SERVER ['HTTP_HOST']. '/images/logo.png" alt="logo">' .$buf;
        $sendEmail->fromEmail = "noreply@" . str_replace ( "www.", "", $_SERVER ['HTTP_HOST'] );
        $sendEmail->attachementFile = "/uploads_contact/" .$file_short;
        $sent = $sendEmail->sendEmail();
      }catch(Exception $e){
        $error = 'There was an error sending the contact email.';
      }
      
      try{
        $sql = "INSERT INTO tbl_contact (contact_name,contact_site,contact_email,contact_phone,contact_method,contact_postcode,contact_store_id,contact_file,contact_details,contact_ip,contact_sent)
            VALUES (:contact_name,:contact_site,:contact_email,:contact_phone,:contact_method,:contact_postcode,:contact_store_id,:contact_file,:contact_details,:contact_ip,:contact_sent)";
        $params = array(":contact_name"=>$_POST['name'],
            ":contact_site"=>$SITE,
            ":contact_email"=>$_POST['email'],
            ":contact_phone"=>$_POST['phone'],
            ":contact_method"=>$_POST['contact-method'],
            ":contact_postcode"=>$_POST['postcode'],
            ":contact_store_id"=>$_POST['listing_id'],
            ":contact_file"=> "uploads_contact/" . $file_short,
            ":contact_details"=>$_POST['comments'],
            ":contact_ip"=>$_SERVER['REMOTE_ADDR'],
            ":contact_sent"=>$sent);
        $DBobject->wrappedSql($sql,$params);
      }catch(Exception $e){
        $error = 'There was an unexpected error saving your question.';
      }
      
      echo json_encode(array(
          'error' => $error,
          'template' => $template
      ));
      exit;
  }
}
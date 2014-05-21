<?php

if(checkToken('frontend',$_POST["formToken"], false)){
  switch($_POST["action"]){
    case 'question':
      global $DBobject,$SITE;
      $error = '';
      
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
      
        
        $to = "apolo@them.com.au";

        $subject = 'Website contact - Contact Us form';
        $body = '<img src="http://'. $_SERVER ['HTTP_HOST']. '/images/logo.png" alt="logo">' .$buf;
        $from = 'Website';
        $fromEmail = "noreply@" . str_replace ( "www.", "", $_SERVER ['HTTP_HOST'] );
        $sent = sendAttachMail($to, $from, $fromEmail, $subject, $body, '', $file_name);
      }catch(Exception $e){
        $error .= 'There was an error sending the contact email.';
      }
/*       
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
      } */
      if(!empty($error)){
      	$_SESSION['error'] = $error;
      	header("Location: {$_SERVER['HTTP_REFERER']}#error");
      }else{
      	header("Location: /thank-you");
      }
      
      exit;
  }
}
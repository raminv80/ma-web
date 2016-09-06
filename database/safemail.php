<?php
/* function SafeMail($to,$subject,$body,$headers,$additional=''){
  $mailSent = mail($to,$subject,$body,$headers,$additional);
  return $mailSent;
} */

function SafeMail($to,$subject,$body,$headers,$additional='', $attachments = array()){
  $mailSent = 0;
  try {
    set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
    require_once 'database/PHPMailer/PHPMailerAutoload.php';
    $mail = new PHPMailer;
    $mail->isHTML(true);
    /*  		$mail->isSMTP();                            // Set mailer to use SMTP
     //  	$mail->SMTPDebug = 2;        				//ENABLE DEBUG  2=client & server messages, 1=client messages
     $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
     $mail->SMTPAuth = true;                               // Enable SMTP authentication
     $mail->Username = 'user@themdigital.com.au';                 // SMTP username
     $mail->Password = 'ABC123';                           // SMTP password
     $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
     $mail->Port = 587;    */                                 // TCP port to connect to

    $header_arr = explode("\r\n", $headers);
    foreach ($header_arr as $val){
      $content = explode(":", $val, 2);
      if(!empty($content[0]) && !empty($content[1])){
        switch ($content[0]){
          case 'Reply-To':
            $address = get_string_between($content[1],'<','>');
            $nameArr = explode("<", trim($content[1]), 2);
            $mail->addReplyTo($address, $nameArr[0]);
            break;
          case 'From':
            $address = get_string_between($content[1],'<','>');
            $nameArr = explode("<", trim($content[1]), 2);
            $mail->From = $address;
            $mail->FromName = $nameArr[0];
            break;
          case 'Bcc':
            $bccArr = explode(',', trim($content[1]));
            foreach($bccArr as $bcc){
              $mail->addBCC($bcc);
            }
            break;
        }
      }
    }
    $toArr = explode(',',$to);
    foreach($toArr as $m){
      $mail->addAddress($m);
    }
    $mail->Subject = $subject;
    $mail->Body    = $body;
    
    //Attachments
    if(!empty($attachments)) {
      foreach($attachments as $att){
        if(file_exists($att)){
          $mail->addAttachment($att);
        }
      }
    }
    
    $mailSent = ($mail->send()?1:0);
    // 		var_dump($mail->ErrorInfo);
  } catch (Exception $e) {
    $error = $e;
  }
  return $mailSent;
}


function get_string_between($string, $start, $end){
  $string = ' ' . $string;
  $ini = strpos($string, $start);
  if ($ini == 0) return '';
  $ini += strlen($start);
  $len = strpos($string, $end, $ini) - $ini;
  return substr($string, $ini, $len);
}



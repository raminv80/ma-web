<?php
/* function SafeMail($to,$subject,$body,$headers,$additional=''){
  $mailSent = mail($to,$subject,$body,$headers,$additional);
  return $mailSent;
} */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function SafeMail($to,$subject,$body,$headers,$additional='', $attachments = array()){
  $mailSent = 0;
  try {
    set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
    $mail = new PHPMailer(true);
    $mail->isHTML(true);
    if(getenv('SMTP_ENABLE')==='true'){
        $mail->isSMTP();
        $mail->SMTPDebug = 2;
        $mail->SMTPAuth = true;
        $mail->Host = getenv('SMTP_HOST');
        $mail->Username = getenv('SMTP_USER');
        $mail->Password = getenv('SMTP_PASSWORD');
        $mail->SMTPSecure = getenv('SMTP_SECURE')==='true';
        $mail->Port = getenv('SMTP_PORT');
    }

    $toArr = explode(',',$to);
    foreach($toArr as $m){
      $mail->addAddress($m);
    }
    $mail->Subject = $subject;

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

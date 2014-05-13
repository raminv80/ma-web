<?php
function SafeMail($to,$subject,$body,$headers){
  $mailSent = mail($to,$subject,$body,$headers);
  return $mailSent;
}
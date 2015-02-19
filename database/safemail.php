<?php
function SafeMail($to,$subject,$body,$headers,$additional=''){
  $mailSent = mail($to,$subject,$body,$headers,$additional);
  return $mailSent;
}

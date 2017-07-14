<?php
set_time_limit ('900');
die('stop');
$url = "http://oldcpanel.steeline.com.au/chris.erends.zip";
grab_file($url,rtrim($_SERVER['DOCUMENT_ROOT'],'/')."/chris.erends.zip");

function grab_file($url,$saveto){
  $ch = curl_init ($url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
  $raw=curl_exec($ch);
  curl_close ($ch);
  if(file_exists($saveto)){
    unlink($saveto);
  }
  $fp = fopen($saveto,'x');
  fwrite($fp, $raw);
  fclose($fp);
}
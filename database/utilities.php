<?php

function getPass($username,$pass){
	return sha1(md5(bin2hex(strrev(stripslashes(strtolower($username))))) . md5(stripslashes($pass)));
}
function getOldPass($username,$pass){
  return sha1(md5(bin2hex(strrev(stripslashes(strtolower($username))))) . md5(stripslashes(strtoupper($pass))));
}
function checkEmail($usr){
	global $DBobject;
	$sql = "SELECT * from tbl_admin WHERE admin_username = :user";
	$res = $DBobject->wrappedSqlGet($sql,array("user" => $usr));

	if(!empty($res)){
		return 'true';
	}
	return 'false';
}

/**
 * Function accepts a string and appends a campaign string to the end.
 * @param string $string
 * @return mixed
 */
function AppendCampaignToString($string) {
  $regex = '#(<a href=")([^"]*)("[^>]*?>)#i';
  return preg_replace_callback($regex, '_appendCampaignToString', $string);
}
function _AppendCampaignToString($match) {
  $url = $match[2];
  if (strpos($url, '?') === false) {
    $url .= '?';
  }
  $url .= '&utm_source=email&utm_medium=email&utm_campaign=product_notify';
  return $match[1].$url.$match[3];
}

/**
 * Function accepts a string and appends google event tracking to file links.
 * @param string $string
 * @return mixed
 */
function AppendEventTrackingToString($string) {
  $regex = '#(<a href=")([^"]*.doc)("[^>]*?>)#i';
  $string = preg_replace_callback($regex, '_appendEventTrackingToString', $string);
  $regex = '#(<a href=")([^"]*.docx)("[^>]*?>)#i';
  $string = preg_replace_callback($regex, '_appendEventTrackingToString', $string);
  $regex = '#(<a href=")([^"]*.txt)("[^>]*?>)#i';
  $string = preg_replace_callback($regex, '_appendEventTrackingToString', $string);
  $regex = '#(<a href=")([^"]*.cad)("[^>]*?>)#i';
  $string = preg_replace_callback($regex, '_appendEventTrackingToString', $string);
  $regex = '#(<a href=")([^"]*.xls)("[^>]*?>)#i';
  $string = preg_replace_callback($regex, '_appendEventTrackingToString', $string);
  $regex = '#(<a href=")([^"]*.xlsx)("[^>]*?>)#i';
  $string = preg_replace_callback($regex, '_appendEventTrackingToString', $string);
  $regex = '#(<a href=")([^"]*.pdf)("[^>]*?>)#i';
  $string = preg_replace_callback($regex, '_appendEventTrackingToString', $string);
  return $string;
}

function _AppendEventTrackingToString($match) {
  $url = $match[2];
  if (strpos($url, '?') === false) {
    $com = array_reverse(explode('/',$url));
    $url .= "\" onClick=\"ga('send', { 'hitType': 'event','eventCategory': 'file','eventAction': 'click','eventLabel': '$com[0]','eventValue': 'download'});";
  }
  return $match[1].$url.$match[3];
}

function urlSafeString($str){
	$str = str_replace('&','and',$str);
	$str = preg_replace("/[\"\']/", "", $str);
	$str = str_replace(' ','-',$str);
	$str = preg_replace('/[^A-Za-z0-9\-]/', '', $str);
	//$str = preg_replace("/[^\s^\d^\w]+/", "", $str);
	$str = strtolower($str);
	$str = preg_replace('/--+/', '-', $str);
	return $str;
}

function urlPrep($str){
	$str = trim($str,'/');
	return $str;
}

function isValidInetAddress($data, $strict = false)
{
	$regex = $strict?
      '/^([.0-9a-z_-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i' :
       '/^([*+!.&#$�\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i'
       ;
       if (preg_match($regex, trim($data), $matches)) {
       	return true;
       } else {
       	return false;
       }
}

function isValidPassword($data)
{
  if(strlen(trim($data))<8){
    return false;
  }

    $rgx = array();
	$rgx[] = '/[A-Z]/';  //Uppercase
	$rgx[] = '/[a-z]/';  //lowercase
	$rgx[] = '/[0-9]/';  //numbers
	$rgx[] = '/[!@#\$%\^&*)(\-._=+]/';  // whatever you mean by 'special char'

	foreach($rgx as $r){
    	if (preg_match_all($r, trim($data), $matches)<1) {
    		return false;
    	}
	}
	return true;
}

function isValidUsername($data){
	if(preg_match('/\W/',$data)){
		return false;
	}
	else{
		return true;
	}
}

function parse_backtrace($raw){
	$output="Trace:<br/>";
	try{
		foreach($raw as $entry){
			$output.="<br/>File: ".$entry['file']." (Line: ".$entry['line'].")<br/>";
			$output.="Function: ".$entry['function']."<br/>";
			try{ $output.="Args: ".implode(", ", $entry['args'])."<br/>"; }catch(Exception $e){}
		}
	}catch(Exception $e){}
	return $output;
}

function formatBytes($size, $precision = 2)
{
	$base = log($size) / log(1024);
	$suffixes = array('', 'k', 'M', 'G', 'T');
	return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}
/**
 * Checks if the string $haystack starts with the string $needle
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
function startsWith($haystack, $needle)
{
	$length = strlen($needle);
	return (strtolower(substr($haystack, 0, $length)) === strtolower($needle));
}
/**
 * Checks if the string $haystack ends with the string $needle
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
function endsWith($haystack, $needle)
{
	$length = strlen($needle);
	$start  = $length * -1; //negative
	return (strtolower(substr($haystack, $start)) === strtolower($needle));
}

/**
 * Generate random-alphanumeric-character string with a given number for its length.
 *
 * @param int $length
 * @return string
 */
function genRandomString($length = 5) {
	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$string = '';
	for ($p = 0; $p < $length; $p++) {
		$string .= $characters[mt_rand(0, strlen($characters))];
	}
	return $string;
}
function logError($trace, $err, $sql = false) {
	global  $CONFIG;
	date_default_timezone_set('Australia/Adelaide');
	$datetime = date('r');
	$msg = "$datetime<br/><br/>$err";
	if(!empty($sql)) {
		$msg .= "<br/>$sql<br/>";
	}

	if(defined('APP_DEBUG') && APP_DEBUG){
		die("$msg<br/>$trace");
	}else{
		sendMail("weberrors@them.com.au", $CONFIG->attributes->company_name.'-Website', 'noreply@website.com.au', 'Error', "$msg<br/>$trace");
		header('Location: /404');
		die();
	}
}


function sendMail($to,$from,$fromEmail,$subject,$body,$bcc=null, $userId = 0, $adminId = 0, $notsendid = 0){
  global $DBobject;
  try{
    if(is_readable($_SERVER['DOCUMENT_ROOT'].'/database/safemail.php')){	require_once 'database/safemail.php';}
  }catch (Exception $e){}

  /* To send HTML mail, you can set the Content-type header. */
  $headers  = "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
  $headers .= "X-Priority: 3\r\n";
  $headers .= "X-Mailer: PHP". phpversion() ."\r\n";

  /* additional headers */
  $headers .= "Reply-To: ". $from . " <".$fromEmail.">\r\n";
  $headers .= "Return-Path: ". $from . " <".$fromEmail.">\r\n";
  $headers .= "From: ". $from . " <".$fromEmail.">\r\n";
  $headers .= "Bcc: medicalert.org.au@gmail.com".(!empty($bcc)?",".$bcc:"")."\r\n";

  $mailSent = 0;
  if(empty($notsendid)){
    //Process immediately
    try{
      $verify = false;
      $sql = "SELECT count(email_id) as cnt FROM tbl_email_queue WHERE email_sent = 1 AND email_modified BETWEEN DATE_SUB(NOW(), INTERVAL 60 MINUTE) AND NOW() ";
      if($resc = $DBobject->executeSQL($sql)){
        $verify = ($resc[0]['cnt'] >= 480)? false : true;
      }

      if(function_exists("SafeMail") && $verify){
        $sql = "SELECT email_id FROM tbl_email_queue WHERE email_ip = :ip AND email_created BETWEEN DATE_SUB(NOW(), INTERVAL 1 MINUTE) AND NOW() LIMIT 5";
        $params = array(
            ":ip"=>$_SERVER['REMOTE_ADDR']
        );
        $res = $DBobject->executeSQL($sql,$params);
        if(count($res) < 5 || !empty($_SESSION['user']['admin']) ){
          $mailSent = SafeMail($to, $subject, $body, $headers, '-f '. $fromEmail);
        }else{
          $mailSent= -1;
        }
      }
    }catch(Exception $e){}

  }else{
    //put in the queue with an id
    $mailSent = $notsendid;
  }

  try{
    $sql = "INSERT INTO tbl_email_queue (email_to, email_from, email_from_email, email_header, email_subject, email_content,email_ip,email_sent,email_user_id,email_admin_id,email_modified,email_ua,email_serverip) VALUES
	      (:to,:from,:from_email,:header,:subject,:content,:ip,:sent,:email_user_id,:email_admin_id,now(),:email_ua,:email_serverip)";
    $params = array(
        ":to"=>$to,
        ":from"=>$from,
        ":from_email"=>$fromEmail,
        ":header"=>$headers,
        ":subject"=>$subject,
        ":content"=>utf8_encode($body),
        ":ip"=>$_SERVER['REMOTE_ADDR'],
        ":sent"=>$mailSent,
        ":email_user_id"=>(empty($userId)?0:$userId),
        ":email_admin_id"=>(empty($adminId)?0:$adminId),
        ":email_ua"=>$_SERVER['HTTP_USER_AGENT'],
        ":email_serverip"=>$_SERVER['SERVER_ADDR']
    );
    $DBobject->executeSQL($sql,$params);
    return $DBobject->wrappedSqlIdentity();
  }catch(Exception $e){}

  return false;
}

/**
 * Create bulk emails in the database. It does NOT send them
 *
 * @param array $to_Array
 * @param string $from
 * @param string $fromEmail
 * @param string $subject
 * @param string $body
 * @return boolean
 */
function createBulkMail($to_Array,$from,$fromEmail,$subject,$body, $adminId = 0, $userKeyArr = array()){
  global $DBobject;

  /* To send HTML mail, you can set the Content-type header. */
  $headers  = "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
  $headers .= "X-Priority: 3\r\n";
  $headers .= "X-Mailer: PHP". phpversion() ."\r\n";

  /* additional headers */
  $headers .= "Reply-To: ". $from . " <".$fromEmail.">\r\n";
  $headers .= "Return-Path: ". $from . " <".$fromEmail.">\r\n";
  $headers .= "From: ". $from . " <".$fromEmail.">\r\n";
  if(getenv('APP_ENV')==='production' || getenv('APP_ENV')==='staging') $headers .= "Bcc: medicalert.org.au@gmail.com\r\n";

  try{
    foreach ($to_Array as $k => $to){
      $sql = "INSERT INTO tbl_email_queue (email_to, email_from, email_from_email, email_header, email_subject, email_content,email_ip,email_sent,email_user_id,email_admin_id,email_modified,email_ua,email_serverip) VALUES
		      (:to,:from,:from_email,:header,:subject,:content,:ip,:sent,:email_user_id,:email_admin_id, now(),:email_ua,:email_serverip)";
      $params = array(
          ":to"=>$to,
          ":from"=>$from,
          ":from_email"=>$fromEmail,
          ":header"=>$headers,
          ":subject"=>$subject,
          ":content"=>utf8_encode($body),
          ":ip"=>$_SERVER['REMOTE_ADDR'],
          ":sent"=>-2,
          ":email_user_id"=> (empty($userKeyArr[$k])?'0':$userKeyArr[$k]),
          ":email_admin_id"=>(empty($adminId)?0:$adminId),
          ":email_ua"=>$_SERVER['HTTP_USER_AGENT'],
          ":email_serverip"=>$_SERVER['SERVER_ADDR']
      );
      $DBobject->executeSQL($sql,$params);
    }
    return true;
  }catch(Exception $e){}
  return false;
}

/**
 * Send bulk emails in the queue. By default it is limited to 100 emails.
 *
 * @return boolean
 */
function sendBulkMail(){
  global $DBobject;
  try{
    if(is_readable($_SERVER['DOCUMENT_ROOT'].'/database/safemail.php')){	require_once 'database/safemail.php';}
  }catch (Exception $e){}

  $cnt = 0;
  $_limit = 50;
  try{
    $verify = false;
    $sql = "SELECT count(email_id) as cnt FROM tbl_email_queue WHERE email_sent = 1 AND email_modified BETWEEN DATE_SUB(NOW(), INTERVAL 60 MINUTE) AND NOW() ";
    if($resc = $DBobject->executeSQL($sql)){
      if($resc[0]['cnt'] >= 480){
        $verify = false;
      }else{
        $verify = true;
        $max = 480 - $resc[0]['cnt'];
        $sendamount = intval($max/2);
        $_limit = ($sendamount >= $_limit)?$_limit: $sendamount;
      }
    }

    if(function_exists("SafeMail") && $verify){
      $sql = "SELECT * FROM tbl_email_queue WHERE email_sent = '-2' OR email_sent = '0' ORDER BY email_sent = 0 DESC,email_created LIMIT $_limit";
      if($res = $DBobject->executeSQL($sql)){
        foreach ($res as $r){
          if(SafeMail($r['email_to'],$r['email_subject'],$r['email_content'],$r['email_header'], '-f '.$r['email_from_email'])){
            $sql = "UPDATE tbl_email_queue SET email_sent = '1',email_modified = now() WHERE email_id = :email_id";
            $DBobject->executeSQL($sql, array(":email_id"=>$r['email_id']));
            $cnt++;
          }
        }
      }
      return $cnt;
    }
  }catch(Exception $e){ die("Error: $e"); }
  return false;
}

function sendAttachMail($to,$from,$fromEmail,$subject,$body,$bcc=null,$attachments=null, $userId = 0, $adminId = 0, $notsendid = 0){
  global $DBobject;
  try{
    if(is_readable($_SERVER['DOCUMENT_ROOT'].'/database/safemail.php')){	require_once 'database/safemail.php';}
  }catch (Exception $e){}

  $mailSent = 0;
  if(empty($notsendid)){
    //Process immediately
    try{
      if(!empty($attachments)) {
        if(!is_array($attachments)) {
          $attachments = array($attachments);
        }
      }

      if(!empty($to) && !empty($subject) && !empty($body) ){
    	   /* To send HTML mail, you can set the Content-type header. */
    	   $headers  = "MIME-Version: 1.0\r\n";
    	   $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    	   $headers .= "X-Priority: 3\r\n";
    	   $headers .= "X-Mailer: PHP". phpversion() ."\r\n";

    	   /* additional headers */
    	   $headers .= "Reply-To: ". $from . " <".$fromEmail.">\r\n";
    	   $headers .= "Return-Path: ". $from . " <".$fromEmail.">\r\n";
    	   $headers .= "From: ". $from . " <".$fromEmail.">\r\n";
    	   if(getenv('APP_ENV')==='production' || getenv('APP_ENV')==='staging') $headers .= "Bcc: medicalert.org.au@gmail.com\r\n";

    	   $verify = false;
    	   $sql = "SELECT count(email_id) as cnt FROM tbl_email_queue WHERE email_sent = 1 AND email_modified BETWEEN DATE_SUB(NOW(), INTERVAL 60 MINUTE) AND NOW() ";
    	   if($resc = $DBobject->executeSQL($sql)){
    	     $verify = ($resc[0]['cnt'] >= 480)? false : true;
    	   }

    	   if(function_exists("SafeMail") && $verify){
    	     $sql = "SELECT email_id FROM tbl_email_queue WHERE email_ip = :ip AND email_created BETWEEN DATE_SUB(NOW(), INTERVAL 1 MINUTE) AND NOW() LIMIT 5";
    	     $params = array(
    	         ":ip"=>$_SERVER['REMOTE_ADDR']
    	     );
    	     $res = $DBobject->executeSQL($sql,$params);
    	     if(count($res) < 5 || !empty($_SESSION['user']['admin']) ){
    	       $mailSent = SafeMail($to, $subject, $body, $headers, '-f '. $fromEmail, $attachments);
    	     }else{
    	       $mailSent= -1;
    	     }
    	   }
      }
    }catch(Exception $e){}

  }else{
    //put in the queue with an id
    $mailSent = $notsendid;
  }

  try{
    $headers = '';
    $sql = "INSERT INTO tbl_email_queue (email_to, email_from, email_from_email, email_header, email_subject, email_content,email_file,email_ip,email_sent,email_user_id,email_admin_id,email_modified,email_ua,email_serverip) VALUES
	      (:to,:from,:from_email,:header,:subject,:content,:file,:ip,:sent,:email_user_id,:email_admin_id,now(),:email_ua,:email_serverip)";
    $params = array(
        ":to"=>$to,
        ":from"=>$from,
        ":from_email"=>$fromEmail,
        ":header"=>$headers,
        ":subject"=>$subject,
        ":content"=>utf8_encode($body),
        ":ip"=>$_SERVER['REMOTE_ADDR'],
        ":file"=>json_encode($attachments),
        ":sent"=>$mailSent,
        ":email_user_id"=>(empty($userId)?0:$userId),
        ":email_admin_id"=>(empty($adminId)?0:$adminId),
        ":email_ua"=>$_SERVER['HTTP_USER_AGENT'],
        ":email_serverip"=>$_SERVER['SERVER_ADDR']
    );
    $DBobject->executeSQL($sql,$params);
    return $DBobject->wrappedSqlIdentity();
  }catch(Exception $e){}

  return false;
}

function sendErrorMail($to,$from,$fromEmail,$subject,$body,$bcc=null){
  try{
    if(is_readable($_SERVER['DOCUMENT_ROOT'].'/database/safemail.php')){	require_once 'database/safemail.php';}
  }catch (Exception $e){}

  /* To send HTML mail, you can set the Content-type header. */
  $headers  = "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
  $headers .= "X-Priority: 3\r\n";
  $headers .= "X-Mailer: PHP". phpversion() ."\r\n";

  /* additional headers */
  $headers .= "Reply-To: ". $from . " <".$fromEmail.">\r\n";
  $headers .= "Return-Path: ". $from . " <".$fromEmail.">\r\n";
  $headers .= "From: ". $from . " <".$fromEmail.">\r\n";
  $headers .= "Bcc: medicalert.org.au@gmail.com".(!empty($bcc)?",".$bcc:"")."\r\n";

  $mailSent = 0;
  try{
    if(function_exists("SafeMail")){
      $mailSent = SafeMail($to, "ERROR: $subject", $body, $headers, '-f '. $fromEmail);
    }
    $filename = $_SERVER['DOCUMENT_ROOT'].'/debug_log';
    $body = time(). PHP_EOL . $subject. PHP_EOL . $body . PHP_EOL;
    file_put_contents($filename, $body, FILE_APPEND | LOCK_EX);

  }catch(Exception $e){}
  return $mailSent;
}


function clean( $str ){
	$type = strtolower(gettype($str));
	switch($type){
		case "array":
			foreach($str as $key => $val){
				$str[$key]= clean($val);
			}
			break;
		case "string":
		default:
			$str = unclean($str); //Unclean the value first to make sure that we are not double cleaning. This needs to be done because add slashes is an unsafe function. It can cause multiple slashes.
			$str = isJson($str) ? $str : htmlentities($str, ENT_QUOTES);
			//$str = htmlspecialchars(trim($str),ENT_QUOTES,'UTF-8',false);
			$str = str_replace("&lt;", "<", $str);
			$str = str_replace("&gt;", ">", $str);
			$str = str_replace("&nbsp;", " ", $str);
			$str = isJson($str) ? $str : addslashes($str);
			break;
	}

	return $str;
}

function htmlclean( $str ){
  $type = strtolower(gettype($str));

  switch($type){
    case "array":
      foreach($str as $key => $val){
        $str[$key] = htmlclean($val);
      }
      break;
    case "string":
    default:
      $str = unclean($str);
      $str = strip_tags($str);
      $str = htmlentities($str, ENT_QUOTES);
      //$str = htmlspecialchars(trim($str),ENT_QUOTES,'UTF-8',false);
      $str = addslashes($str);
      break;
  }
  return $str;
}

function htmlclean_withban( $str ){
	$type = strtolower(gettype($str));

	switch($type){
		case "string":
			if(preg_match("/script/i", $str) && hasTags($str)){ sendtoforbidden(); }
			if(preg_match("/base64_encode/i", $str)){ sendtoforbidden(); }
			if(preg_match("/globals=/i", $str)){ sendtoforbidden(); }
			if(preg_match("/_request/i", $str)){ sendtoforbidden(); }
			$str = unclean($str);
			$str = strip_tags($str);
			$str = htmlspecialchars(trim($str),ENT_QUOTES,'UTF-8',false);
			$str = addslashes($str);
			break;

		case "array":
			foreach($str as $key => $val){
				if(preg_match("/script/i", $key) && hasTags($str)){ sendtoforbidden(); }
				if(preg_match("/base64_encode/i", $key)){ sendtoforbidden(); }
				if(preg_match("/globals=/i", $key)){ sendtoforbidden(); }
				if(preg_match("/_request/i", $key)){ sendtoforbidden(); }
				$str[$key] = htmlclean_withban($val);
			}
			break;
		default:
			if(preg_match("/script/i", $str) && hasTags($str)){ sendtoforbidden(); }
			if(preg_match("/base64_encode/i", $str)){ sendtoforbidden(); }
			if(preg_match("/globals=/i", $str)){ sendtoforbidden(); }
			if(preg_match("/_request/i", $str)){ sendtoforbidden(); }
			$str = unclean($str);
			$str = strip_tags($str);
			$str = htmlspecialchars(trim($str),ENT_QUOTES,'UTF-8',false);
			$str = addslashes($str);
			break;
	}
	return $str;
}
function hasTags( $str )
{
	return !(strcmp( $str, strip_tags($str ) ) == 0);
}


function sendtoforbidden(){
	GLOBAL $DBobject;
	if($DBobject){
		$params = array(
				"ip"=>$_SERVER['REMOTE_ADDR'],
				"uri"=>$_SERVER['REQUEST_URI'],
				"request"=>json_encode($_REQUEST),
				"get"=>json_encode($_GET),
				"post"=>json_encode($_POST)
		);
		$sql = "INSERT INTO badrequest_log (ip,uri,request,get,post) VALUES (:ip,:uri,:request,:get,:post)";
		$DBobject->wrappedSql($sql,$params);

		$sql = "SELECT COUNT(id) AS cnt FROM badrequest_log WHERE created > DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND ip = :ip";
		$res = $DBobject->executeSQL($sql,array("ip"=>$_SERVER['REMOTE_ADDR']));
		if($res[0]['cnt'] >= 3){
			$sql = "INSERT INTO badrequest_blocked (ip) VALUES (:ip)";
			$res = $DBobject->executeSQL($sql,array("ip"=>$_SERVER['REMOTE_ADDR']));
		}
	}

	header("HTTP/1.1 403 Forbidden");
	header("Location: /403.html");
	die();
}

function checkforbidden(){
	GLOBAL $DBobject;
	if($DBobject){
		$sql = "SELECT COUNT(id) AS cnt FROM badrequest_blocked WHERE created > DATE_SUB(NOW(), INTERVAL 1 DAY) AND deleted IS NULL AND ip = :ip";
		$res = $DBobject->wrappedSql($sql,array("ip"=>$_SERVER['REMOTE_ADDR']));
		if($res[0]['cnt'] == 0){
			return false;
		}
	}

	header("HTTP/1.1 403 Forbidden");
	header("Location: /403.html");
	die();
}

function unclean( $str ){
	$type = strtolower(gettype($str));
	switch($type){
		case "array":
			foreach($str as $key => $val){
				$str[$key]= unclean($val);
			}
			break;
		case "string":
		default:
			$str = str_replace(chr(146),"'",$str);
			$str = str_replace(chr(145),"'",$str);
			$str = stripslashes($str);
			$str = str_replace('&nbsp;',' ',$str);
			$str = html_entity_decode($str,ENT_QUOTES,'UTF-8');
			break;
	}
	return $str;
}

/**
 * Get a specific token if not created then generate it
 *
 * @param string $name
 * @return string
 */
function getToken($name){
	if(!empty($_SESSION[$name . "-token"])){
		return $_SESSION[$name . "-token"];
	}else{
		$_SESSION[$name . "-token"] = generatetoken();
		return $_SESSION[$name . "-token"];
	}
}

/**
 * Generate token
 *
 * @return string
 */
function generatetoken(){
    $lRange = rand(50,rand());
    $rRange = rand(500,23493244);
    if($lRange>$rRange){
        $t=$lRange;
        $lRange=$rRange;
        $rRange=$t;
    }

	$token = sha1($_SERVER['REMOTE_ADDR'].md5(date("D M j G:i:s T Y")). md5(uniqid(mt_rand($lRange, $rRange),true)));
	return $token;
}

/**
 * Verify specific token and optionally renew it when valid
 *
 * @param string $name
 * @param string $token
 * @param boolean $deleteAfterValidCheck
 * @return boolean
 */
function checkToken($name, $token, $deleteAfterValidCheck = false){
	if($_SESSION[$name . "-token"] == $token && !empty($token)){
		$isValid = true;
		if ($deleteAfterValidCheck) {
			$_SESSION[$name . "-token"] = '';
		}
	}else{
		$isValid = false;
	}
	return $isValid;
}

/*
 *
* Gets distance over 2 places
*
*/
function calc_dist($latitude1, $longitude1, $latitude2, $longitude2) {
	$thet = $longitude1 - $longitude2;
	$dist = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($thet)));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$kmperlat = 111.325;
	$dist = $dist * $kmperlat;
	return (round($dist));
}

/**
 * Checks if a value exists in an multidimensional array.
 * Searches haystack for needle using loose comparison unless strict is set.
 *
 * @param mixed $needle
 * @param array $haystack
 * @param boolean $strict
 * @return boolean
 */
function in_array_r($needle, $haystack, $strict = false) {
	foreach ($haystack as $item) {
		if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
			return true;
		}
	}
	return false;
}

/**
 * Convert an associative array into csv string with header based on the keys
 *
 * @param  $array
 * @return string
 */
function AssociativeArrayToCSV($array){
	$head = "";
	$buf = "";
	foreach($array as $key => $val){
		if(is_array($val)){
			foreach($val as $key => $field){
				if( $val === reset($array)){
					$head .= "$key,";
				}
				$buf.= '"'. $field . '",';
			}
		}
		$buf.= "\r\n";
	}
	return $head."\r\n".$buf;
}


/**
 * Convert an associative array into a | delimited txt
 *
 * @param  $array
 * @return string
 */
function AssociativeArrayToTXT($array){
  $head = "";
  $buf = "";
  foreach($array as $key => $val){
    if(is_array($val)){
      $chkIfFirstCol = 0;
      foreach($val as $key => $field){
        if( $val === reset($array)){
          if($chkIfFirstCol == 0){
            $head .= "$key";
          }else{
            $head .= "|$key";
          }
        }
        if($chkIfFirstCol== 0){
          $buf.= $field;
        }else{
          $buf.= '|"'. $field.'"';
        }
        $chkIfFirstCol++;
      }
    }
    $buf.= "\r\n";
  }
  return $head."\r\n".$buf;
}

/**
 * Convert an array into csv string (No header)
 *
 * @param  $array
 * @return string
 */
function arrayToCSV($array){
	$buf = "";
	foreach($array as $key => $val){
		if(is_array($val)){
			foreach($val as $key => $field){
				$buf.= '"'. $field . '",';
			}
		}
		$buf.= "\r\n";
	}
	return $buf;
}

/**
 * Tries to identify if user is on a mobile based on the USER_AGENT string
 * @return boolean
 */
function isMobile(){
  $useragent=$_SERVER['HTTP_USER_AGENT'];
  if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
    return true;
  }
  return false;
}

/**
 * Return true when string is a valid json
 *
 * @param string $string
 * @return boolean
 */
function isJson($string){
  json_decode($string);
  return (json_last_error() == JSON_ERROR_NONE);
}

/**
 * Date difference between two dates
 * Optional:
 * $differenceFormat - Dateformat. Default number of days.
 *
 * @param string $_date1
 * @param string $_date2
 * @param string $differenceFormat
 */
function dateDifference($_date1 , $_date2 , $differenceFormat = '%R%a' )
{
	$datetime1 = date_create($_date1);
	$datetime2 = date_create($_date2);

	$interval = date_diff($datetime1, $datetime2);

	return $interval->format($differenceFormat);
}

/**
 * Return associative array with parents info (admin_level and admin_id)
 *
 * @param int $parentId
 * @param int $root
 * @param array $list
 * @return array
 */
function getAdminParents($parentId, $root = 0, $list = array()) {
	global $DBobject;

	$sql = "SELECT admin_parent_id, admin_level FROM tbl_admin WHERE (admin_deleted IS NULL OR admin_deleted = '0000-00-00') AND admin_id = :id";
	if($res = $DBobject->wrappedSql($sql,array(
			":id"=>$parentId
	))){
		$list["{$res[0]['admin_level']}"] = $parentId;
		if($res[0]['admin_parent_id'] > $root && $res[0]['admin_parent_id'] != $parentId){
			return getAdminParents($res[0]['admin_parent_id'], $root, $list);
		}
	}
	return $list;
}


/**
 * Return array with all children given the admin_id
 *
 * @param int $parentId
 * @param int $root
 * @param array $list
 * @return array
 */
function getAdminChildren($parentId, $root = 0, $list = array()) {
	global $DBobject;

	if(!in_array($parentId,$list)){
		$list[] = $parentId;
	}
	$sql = "SELECT admin_id FROM tbl_admin WHERE (admin_deleted IS NULL OR admin_deleted = '0000-00-00') AND admin_parent_id = :id";
	if($res = $DBobject->wrappedSql($sql,array(
			":id"=>$parentId
	))){
		foreach ($res as $r){
			if(!in_array($r['admin_id'],$list) && $r['admin_id'] > 0 && $r['admin_id'] != $root){
				return getAdminChildren($r['admin_id'], $root, $list);
			}
		}
	}
	return $list;
}

/**
 * Set user authentication cookie.
 * $userId: default 0 (if null/zero or value does not match with any record then will unset the cookie).
 * $name: default 'usrauth'
 *
 * @param string $name
 * @param int $userId
 * @return boolean
 */
function SetUserAuthCookie($name ='usrauth', $userId = 0) {
	global $DBobject;

	// Get user unique-hash (user_password)
	$userStr = '';
	if(!empty($userId)){
		$sql = "SELECT user_password FROM tbl_user WHERE (user_deleted IS NULL OR user_deleted = '0000-00-00') AND user_id = :id";
		if($res = $DBobject->wrappedSql($sql,array(":id"=>$userId))){
			$userStr = $res[0]['user_password']; // string (40 char)
		}
	}

	$value = '';
	$expTime = time() - (60*60*24*14); // past 14 days
	if(!empty($userStr)){
		$browser = md5($_SERVER['HTTP_USER_AGENT']); // string (32 char)
		$expTime = time()+(60*60*24*14); // next 14 days
		$expTimeStr = dechex($expTime); // string (+8 char)

		// Build cookie string value
		$value = $userStr . $browser . $expTimeStr;
	}

	//SET COOKIE
	$_SECURE_COOKIE = false;
	if($_SERVER['SERVER_PORT'] == 443 || !empty($_SERVER['HTTPS'])){
		$_SECURE_COOKIE = true; /*IF HTTPs TURN THIS ON */
	}

	$currentCookieParams = session_get_cookie_params();

	setcookie($name,//name
	$value,//value
	$expTime,//expires at end of session
	$currentCookieParams['path'],//path
	$currentCookieParams['domain'],//domain
	$_SECURE_COOKIE, //secure
	true  //httponly: Only accessible via http. Not accessible to javascript
	);

	if(empty($value)){
		unset($_COOKIE[$name]);
		return false;
	}
	return true;
}


/**
 * Check user authentication cookie.
 * $name: default 'usrauth'
 *
 * @param string $name
 * @return array
 */
function checkUserAuthCookie($name ='usrauth') {
	global $DBobject;

	if(!empty($_COOKIE[$name]) && strlen($_COOKIE[$name]) > 79 && strlen($_COOKIE[$name]) < 82 ){

		// Get variables
		$userStr = substr($_COOKIE[$name], 0, 40);
		$browser = substr($_COOKIE[$name], 40, 32);
		$expTime = hexdec(substr($_COOKIE[$name], 72));

		// Validate expiration date
		if($expTime > time() && $expTime <= (time()+(60*60*24*14)) ){
			// validate browser
			if($browser == md5($_SERVER['HTTP_USER_AGENT'])){
				// Validate user hash (user_password)
				$sql = "SELECT * FROM tbl_user WHERE (user_deleted IS NULL OR user_deleted='0000-00-00') AND user_password = :user_password";
				if($res = $DBobject->wrappedSql($sql,array(":user_password"=>$userStr))){
					return array(
							"id"=>$res[0]["user_id"],
							"gname"=>$res[0]["user_gname"],
							"surname"=>$res[0]["user_surname"],
							"email"=>$res[0]["user_email"],
							"loggedInByCookie"=>true
					);
				}
			}
		}
	}
	// Clear user authentication cookie
	SetUserAuthCookie($name);
	return false;
}


function sendGAEvent($_tid,$_category,$_action,$_label="",$_value=0,$_cid=null){
  if(empty($_tid) || empty($_category) || empty($_action)) return false;

  //Submit Google Event
  // Standard params
  $v = 1;
  $tid = $_tid; // Put your own Analytics ID in here
  $cid = !empty($_cid)?$_cid:gaParseCookie();

  $dh = !empty($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:$_SERVER['HTTP_HOST'];

  $info = array();
  $info['category'] = $_category; // Event category
  $info['action'] = $_action; // event action
  $info['label'] = $_label; // event label
  $info['value'] = $_value;


  // Set up Transaction params
  $ec = $info['category']; // Event category
  $ea = $info['action']; // event action
  $el = $info['label']; // event label
  $ev = $info['value']; // event value
  $dp = !empty($_SERVER['HTTP_REFERER'])? parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) : "/API/";

  // Send Transaction hit
  $data = array(
      'v' => $v,
      'tid' => $tid,
      'cid' => $cid,
      'uip' => $_SERVER['REMOTE_ADDR'],
      't' => 'event',
      'ec' => $ec,
      'ea' => $ea,
      'el' => $el,
      'ev' => $ev,
      'dh' => $dh,
      'dp' => $dp
  );
  return gaFireHit($data);
}

/**
 * Ecommerce Tracking  - Measuring Purchases
 *
 * @param string $_tid
 * @param array $_totalArr
 * @param array $_cartitemArr
 * @return boolean
 */
function sendGAEcPurchase($_tid,$_totalArr,$_cartitemArr,$_cid=null){
	if(empty($_tid) || empty($_totalArr) || empty($_cartitemArr)) return false;

	$v = 1;
	$tid = $_tid; // Put your own Analytics ID in here
	$cid = !empty($_cid)?$_cid:gaParseCookie();
	$dh = !empty($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:$_SERVER['HTTP_HOST'];

	// Send Transaction hit
	$data = array(
			'v' => $v,
			'tid' => $tid,
			'cid' => $cid,
			't' => 'transaction',
			'ti' => $_totalArr['id'],
			'ta' => $dh.'-ecommerce',
			'tr' => $_totalArr['total'],
			'tt' => $_totalArr['tax'],
			'ts' => $_totalArr['shipping'],
			'cu' => 'AUD'
	);
	gaFireHit($data);

	// Send Item hit
	foreach($_cartitemArr as $item){
		$data = array(
				'v' => $v,
				'tid' => $tid,
				'cid' => $cid,
				't' => 'item',
				'ti' => $_totalArr['id'],
				'in' => $item['name'],
				'ip' => $item['price'],
				'iq' => $item['quantity'],
				'ic' => $item['id'],
				'iv' => $item['variant'],
				'cu' => 'AUD'
		);
		gaFireHit($data);
	}
	return true;
}


/**
 * Enhanced Ecommerce Tracking  - Combining Impressions and Actions
 *
 * @param string $_tid
 * @param string $_action
 * @param array $_proditemArr
 * @param string $_impressionList
 * @return boolean
 */
function sendGAEnEcImpressionAction($_tid, $_action, $_proditemArr, $_impressionList = null, $_cid=null){
  if(empty($_tid) || empty($_action) || empty($_proditemArr)) return false;

  $v = 1;
  $tid = $_tid; // Put your own Analytics ID in here
  $cid = !empty($_cid)?$_cid:gaParseCookie();
  $dh = !empty($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:$_SERVER['HTTP_HOST'];
  $dl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  $dp = $_SERVER['REQUEST_URI'];

  if(strpos($cid, '.') === false) {
    // Send page view
    $data = array(
        'v' => $v,
        'tid' => $tid,
        'cid' => $cid,
        't' => 'pageview',
        'dl' => $dl,
        'dh' => $dh,
        'dp' => $dp
    );
    gaFireHit($data);
  }

  // Send Transaction hit
  $data = array(
      'v' => $v,
      'tid' => $tid,
      'cid' => $cid,
      'uip' => $_SERVER['REMOTE_ADDR'],
      't' => 'event',
      'ec' => 'UX',
      'ea' => 'click',
      'el' => 'Results',
      'pa' => $_action
  );

  $k = 1;
  foreach($_proditemArr as $p){
    $data["pi{$k}id"] = $p['id'];
    $data["pi{$k}nm"] = $p['name'];
    $data["pi{$k}ca"] = $p['category'];
    $data["pi{$k}br"] = $p['brand'];
    $data["pi{$k}va"] = $p['variant'];
    $data["pi{$k}ps"] = $p['position'];
    if(!empty($_impressionList)){
      $data["il1nm"] = $_impressionList;
      $data["il1pi{$k}id"] = $p['id'];
      $data["il1pi{$k}nm"] = $p['name'];
      $data["il1pi{$k}ca"] = $p['category'];
      $data["il1pi{$k}br"] = $p['brand'];
      $data["il1pi{$k}va"] = $p['variant'];
      $data["il1pi{$k}ps"] = $p['position'];
    }
    $k++;
  }

  return gaFireHit($data);
}


/**
 * Enhanced Ecommerce Tracking  - Measuring Action
 *
 * PRODUCTS AND PROMOTION ACTIONS
 * -click: 	A click on a product or product link for one or more products.
 * -detail: 	A view of product details.
 * -add: 	Adding one or more products to a shopping cart.
 * -remove: 	Remove one or more products from a shopping cart.
 * -checkout: 	Initiating the checkout process for one or more products.
 * -checkout_option: 	Sending the option value for a given checkout step.
 * -purchase: 	The sale of one or more products.
 * -refund: 	The refund of one or more products.
 * -promo_click: 	A click on an internal promotion.

 * @param string $_tid
 * @param string $_action
 * @param array $_cartitemArr
 * @return boolean
 */
function sendGAEnEcAction($_tid, $_action, $_cartitemArr, $_impressionList = null, $_cid=null){
	if(empty($_tid) || empty($_action) || empty($_cartitemArr)) return false;

	$v = 1;
	$tid = $_tid; // Put your own Analytics ID in here
	$cid = !empty($_cid)?$_cid:gaParseCookie();
	$dh = !empty($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:$_SERVER['HTTP_HOST'];

	// Send Transaction hit
	$data = array(
			'v' => $v,
			'tid' => $tid,
			'cid' => $cid,
			't' => 'event',
			'ec' => 'UX',
			'ea' => 'click',
			'el' => $_action,
			'pa' => $_action,
	    'pal' => parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH),
			'pr1id' => $_cartitemArr['id'],
			'pr1nm' => $_cartitemArr['name'],
			'pr1ca' => $_cartitemArr['category'],
			'pr1br' => $_cartitemArr['brand'],
			'pr1va' => $_cartitemArr['variant'],
			'pr1pr' => $_cartitemArr['price'],
			'pr1ps' => $_cartitemArr['position']
	);
	if(!empty($_impressionList)){
	  $data["il1nm"] = $_impressionList;
	  $data["il1pi1id"] = $_cartitemArr['id'];
	  $data["il1pi1nm"] = $_cartitemArr['name'];
	  $data["il1pi1ca"] = $_cartitemArr['category'];
	  $data["il1pi1br"] = $_cartitemArr['brand'];
	  $data["il1pi1va"] = $_cartitemArr['variant'];
	  $data["il1pi1ps"] = $_cartitemArr['position'];
	}
	return gaFireHit($data);
}


/**
 * Enhanced Ecommerce Tracking  - Measuring Checkout Steps
 *
 * @param string $_tid
 * @param string $_stepOption
 * @param array $_cartitemArr
 * @return boolean
 */
function sendGAEnEcCheckoutStep($_tid, $_stepNumber = 1, $_stepName = 'N/A', $_cartitemArr, $_cid=null){
	if(empty($_tid) || empty($_cartitemArr)) return false;

	$v = 1;
	$tid = $_tid; // Put your own Analytics ID in here
	$cid = !empty($_cid)?$_cid:gaParseCookie();
	$dh = !empty($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:$_SERVER['HTTP_HOST'];

	// Send Transaction hit
	$data = array(
			'v' => $v,
			'tid' => $tid,
			'cid' => $cid,
			't' => 'pageview',
			'dh' => $dh,
			'dp' => $_SERVER['HTTP_REFERER'],
			'pa' => 'checkout',
			'cos' => $_stepNumber,
			'col' => $_stepName
	);
	$cnt = 1;
	foreach($_cartitemArr as $item){
		$data["pr{$cnt}id"] = $item['id'];
		$data["pr{$cnt}nm"] = $item['name'];
		$data["pr{$cnt}ca"] = $item['category'];
		$data["pr{$cnt}br"] = $item['brand'];
		$data["pr{$cnt}va"] = $item['variant'];
		$data["pr{$cnt}pr"] = $item['price'];
		$data["pr{$cnt}qt"] = $item['quantity'];
		$cnt++;
	}

	$response = gaFireHit($data);
	return $response;
}


/**
 * Enhanced Ecommerce Tracking  - Measuring Checkout Options
 *
 * @param string $_tid
 * @param string $_stepOption
 * @param integer $_step
 * @return boolean
 */
function sendGAEnEcCheckoutOptions($_tid, $_stepNumber, $_stepOption,  $_cid=null){
	if(empty($_tid) || empty($_stepOption) || empty($_step)) return false;

	$v = 1;
	$tid = $_tid; // Put your own Analytics ID in here
	$cid = !empty($_cid)?$_cid:gaParseCookie();

	// Send Transaction hit
	$data = array(
			'v' => $v,
			'tid' => $tid,
			'cid' => $cid,
			't' => 'event',
			'ec' => 'Checkout',
			'ea' => 'Option',
			'pa' => 'checkout_option',
			'cos' => $_stepNumber,
			'col' => $_stepOption
	);
	return gaFireHit($data);
}


/**
 * Enhanced Ecommerce Tracking  - Measuring Purchases
 *
 * @param string $_tid
 * @param array $_totalArr
 * @param array $_cartitemArr
 * @return boolean
 */
function sendGAEnEcPurchase($_tid,$_totalArr,$_cartitemArr,$_cid=null){
	if(empty($_tid) || empty($_totalArr) || empty($_cartitemArr)) return false;

	$v = 1;
	$tid = $_tid; // Put your own Analytics ID in here
	$cid = !empty($_cid)?$_cid:gaParseCookie();
	$dh = !empty($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:$_SERVER['HTTP_HOST'];

	// Send Transaction hit
	$data = array(
			'v' => $v,
			'tid' => $tid,
			'cid' => $cid,
			't' => 'pageview',
			'dh' => $dh,
			'dp' => $_SERVER['HTTP_REFERER'],
			'ti' => $_totalArr['id'],
			'ta' => $dh.'-ecommerce',
			'tr' => $_totalArr['total'],
			'tt' => $_totalArr['tax'],
			'ts' => $_totalArr['shipping'],
			'tcc' => $_totalArr['coupon'],
			'pa' => 'purchase'
	);
	$cnt = 1;
	foreach($_cartitemArr as $item){
		$data["pr{$cnt}id"] = $item['id'];
		$data["pr{$cnt}nm"] = $item['name'];
		$data["pr{$cnt}ca"] = $item['category'];
		$data["pr{$cnt}br"] = $item['brand'];
		$data["pr{$cnt}va"] = $item['variant'];
		$data["pr{$cnt}pr"] = $item['price'];
		$data["pr{$cnt}ps"] = $item['position'];
		$data["pr{$cnt}qt"] = $item['quantity'];
		$cnt++;
	}

	$response = gaFireHit($data);
	return $response;
}

// See https://developers.google.com/analytics/devguides/collection/protocol/v1/devguide
function gaFireHit( $data = null ) {
  if($data){
    try{
      $getString = 'https://ssl.google-analytics.com/collect';
      $getString .= '?payload_data&';
      $getString .= http_build_query($data);

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $getString);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_exec($ch);
      return true;
    }catch(Exception $e){}
  }
  return false;
}

// Handle the parsing of the _ga cookie or setting it to a unique identifier
function gaParseCookie() {
  if (isset($_COOKIE['_ga'])) {
    list($version,$domainDepth, $cid1, $cid2) = explode('.', $_COOKIE["_ga"],4);
    $contents = array('version' => $version, 'domainDepth' => $domainDepth, 'cid' => $cid1.'.'.$cid2);
    $cid = $contents['cid'];
  }elseif (!empty($_POST["_ga"])) {
    list($version,$domainDepth, $cid1, $cid2) = explode('.', $_POST["_ga"],4);
    $contents = array('version' => $version, 'domainDepth' => $domainDepth, 'cid' => $cid1.'.'.$cid2);
    $cid = $contents['cid'];
  }
  else $cid = gaGenUUID();
  return $cid;
}

// Generate UUID v4 function - needed to generate a CID when one isn't available
function gaGenUUID() {
  return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      // 32 bits for "time_low"
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
      // 16 bits for "time_mid"
      mt_rand( 0, 0xffff ),
      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand( 0, 0x0fff ) | 0x4000,
      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand( 0, 0x3fff ) | 0x8000,
      // 48 bits for "node"
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
  );
}

function saveInLog($ACTION, $TABLE, $ID, $ADDITIONAL = ''){
	global $DBobject;
	if(empty($TABLE) || empty($ID)){
		return false;
	}
	$params = array (
			":log_admin_id" => $_SESSION['user']['admin']["id"],
			":log_action" => $ACTION,
			":log_record_id" => $ID,
			":log_record_table" => $TABLE,
			":log_additional" => $ADDITIONAL,
			":log_ip" => $_SERVER['REMOTE_ADDR'],
			":log_browser" => $_SERVER['HTTP_USER_AGENT'],
			":log_referer" => $_SERVER['HTTP_REFERER']
	);

	$sql = "INSERT INTO tbl_log ( log_admin_id, log_action, log_record_table, log_record_id, log_additional, log_ip, log_browser, log_referer )
							values( :log_admin_id, :log_action, :log_record_table, :log_record_id, :log_additional, :log_ip, :log_browser, :log_referer)";
	return $DBobject->wrappedSql($sql, $params);
}


function sendSMS($recipients = array(), $message, $adminId = 0){
	global $DBobject, $CONFIG, $HTTP_HOST;
	require_once('includes/plugins/mmsoap/MMSoap.php');

	// Set up account details
	$username 	= 	(string) $CONFIG->sms->username;
	$password 	= 	(string) $CONFIG->sms->password;
	$origin   	=   (string) $CONFIG->sms->origin;
	$soap = new MMSoap($username, $password);
	$error = array();
	$response = array();
	$cnt = 0;

	try{
		foreach($recipients as $user_id => $to){
			if(!empty($to)) {

				// Create new MMSoap class
				$response[$user_id] = $soap->sendMessages(array($to), html_entity_decode(utf8_decode($message),ENT_QUOTES), null, $origin);
				$result = $response[$user_id]->getResult();

				#if failed, send email to THEM
				if($result->sent){
					$sent = 1;
				}else{
					$sent = 0;
					$error[] =  "Failed[$user_id]:  $to";
				}

				// Store in log
				$sql = "INSERT INTO tbl_sms (sms_admin_id, sms_user_id, sms_to, sms_content, sms_ip, sms_sent) VALUES
			      (:sms_admin_id, :sms_user_id, :sms_to, :sms_content, :sms_ip, :sms_sent)";
				$params = array(
						":sms_admin_id"=>$adminId,
						":sms_user_id"=>$user_id,
						":sms_to"=>$to,
						":sms_content"=>$message,
						":sms_ip"=>$_SERVER['REMOTE_ADDR'],
						":sms_sent"=>$sent
				);
				$DBobject->executeSQL($sql,$params);
				if($sent == 1) $cnt++;
			}
		}

	}catch(Exception $e){
		$error[] = $e;
	}
	if(!empty($error)){
		$to = getenv('EMAIL_APP_SUPPORT');
		sendMail($to, (string) $CONFIG->company->name, 'noreply@' . str_replace ( "www.", "", $GLOBALS['HTTP_HOST'] ), 'SMS function error', "Error: ".print_r($error,TRUE)." </br> Session: ".print_r($_SESSION,TRUE));
	}
	return array('response'=>$response, 'sent'=>$cnt, 'error'=>$error);
}

function getShortURL($longUrl, $apiKey = '') {
	$jsonData = json_encode(array('longUrl' => $longUrl));
	$curlObj = curl_init();
	curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url'. (!empty($apiKey)?'?key='.$apiKey:''));
	curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curlObj, CURLOPT_HEADER, 0);
	curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
	curl_setopt($curlObj, CURLOPT_POST, true);
	curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);
	$response = curl_exec($curlObj);
	$json = json_decode($response);
	curl_close($curlObj);

	if(!empty($json->id))	return $json->id;
	return $longUrl;
}



function geocode($city){
	$cityclean = str_replace (" ", "+", $city);
	$details_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($cityclean) . "&sensor=false";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $details_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$geoloc = json_decode(curl_exec($ch), true);
	curl_close($ch);

	return $geoloc['results'][0]['geometry']['location'];
}


function validateDate($date, $format = 'd/m/Y'){
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) === $date;
}

function pretty_print($var){
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

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
	$r1='/[A-Z]/';  //Uppercase
	$r2='/[a-z]/';  //lowercase
	$r3='/[!@#$%^&*()-_=+{};:,<.>]/';  // whatever you mean by 'special char'
	$r4='/[0-9]/';  //numbers

	$regex = '/[A-Z]/';
	if (preg_match_all($regex, trim($data), $matches)<1) {
		return false;
	}
	$regex = '/[a-z]/';
	if (preg_match_all($regex, trim($data), $matches)<1) {
		return false;
	}
	$regex='/[0-9]/';
	if (preg_match_all($regex, trim($data), $matches)<1) {
		return false;
	}
	if(strlen(trim($data))<8){
		return false;
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
	foreach($raw as $entry){
		$output.="<br/>File: ".$entry['file']." (Line: ".$entry['line'].")<br/>";
		$output.="Function: ".$entry['function']."<br/>";
		$output.="Args: ".implode(", ", $entry['args'])."<br/>";
	}
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
	$debug = (string)$CONFIG->attributes()->debug;
	if($debug == 'true'){
		die("$msg<br/>$trace");
	}else{
		sendMail("cmsemails@them.com.au", $CONFIG->attributes->company_name.'-Website', 'noreply@website.com.au', 'Error', "$msg<br/>$trace");
		header('Location: /404');
		die();
	}
}

function sendMail($to,$from,$fromEmail,$subject,$body,$bcc=null){
	global $DBobject;
	require_once 'database/safemail.php';

	/* To send HTML mail, you can set the Content-type header. */
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "X-Priority: 3\r\n";
	$headers .= "X-Mailer: PHP". phpversion() ."\r\n";

	/* additional headers */
	$headers .= "Reply-To: ". $from . " <".$fromEmail.">\r\n";
	$headers .= "Return-Path: ". $from . " <".$fromEmail.">\r\n";
	$headers .= "From: ". $from . " <".$fromEmail.">\r\n";
	$headers .= "Bcc: cmsemails@them.com.au".(!empty($bcc)?",".$bcc:"")."\r\n";

	$mailSent = 0;
	try{
	  if(function_exists("SafeMail")){
	  	$sql = "SELECT email_id FROM tbl_email_copy WHERE email_ip = :ip AND email_created BETWEEN DATE_SUB(NOW(), INTERVAL 5 MINUTE) AND NOW() LIMIT 5";
	  	$params = array(
	  			":ip"=>$_SERVER['REMOTE_ADDR']
	  	);
	  	$res = $DBobject->executeSQL($sql,$params);
	  	if(count($res) < 5 ){
	    	$mailSent = (SafeMail($to,$subject,$body,$headers, "-f $fromEmail"))?1:0;;
	  	}else{
				$mailSent= -1;
			}
	  }
	}catch(Exception $e){}
	
	try{
	  $sql = "INSERT INTO tbl_email_copy (email_to, email_header, email_subject, email_content,email_ip,email_sent,email_sender_useragent,email_time) VALUES 
	      (:to,:header,:subject,:content,:ip,:sent,:useragent,:time)";
	  $params = array(
	      ":to"=>$to,
	      ":header"=>$headers,
	      ":subject"=>$subject,
	      ":content"=>utf8_encode($body),
	      ":ip"=>$_SERVER['REMOTE_ADDR'],
  		  ":sent"=>$mailSent,
  		  ":useragent"=>$_SERVER['HTTP_USER_AGENT'],
  		  ":time"=>date("d/m/Y H:i:s T(P)"),
	  );
	  $DBobject->executeSQL($sql,$params);
	}catch(Exception $e){}
	return $mailSent;
}

function sendAttachMail($to,$from,$fromEmail,$subject,$body,$bcc=null,$attachmentFile=null){
	global $DBobject;
	require_once 'database/mail/Mail.php';
	require_once 'database/mail/AttachmentMail.php';
	require_once 'database/mail/Multipart.php';
	
	$mailSent = 0;
	try{
		if(!empty($to) && !empty($subject) && !empty($body) ){
			$mail = new AttachmentMail($to, $subject, $from, $fromEmail);
	
			if(!empty($attachmentFile) && file_exists($attachmentFile)){
				$mp1 = new Multipart($attachmentFile);
				$mail->addAttachment($mp1);
			}
			$mail->addBCC('cmsemails@them.com.au'.(!empty($bcc)?",".$bcc:""));
			$mail->setHtml($body);
	
			$sql = "SELECT email_id FROM tbl_email_copy WHERE email_ip = :ip AND email_created BETWEEN DATE_SUB(NOW(), INTERVAL 5 MINUTE) AND NOW() LIMIT 5";
			$params = array(
					":ip"=>$_SERVER['REMOTE_ADDR']
			);
			$res = $DBobject->executeSQL($sql,$params);
			if(count($res) < 5 ){
				$mailSent = ($mail->send())?1:0;;
			}else{
				$mailSent= -1;
			}
		}
	}catch(Exception $e){}

	try{
		$headers = '';
		$sql = "INSERT INTO tbl_email_copy (email_to, email_header, email_subject, email_content,email_ip,email_sent,email_sender_useragent,email_time) VALUES 
	      (:to,:header,:subject,:content,:file,:ip,:sent,:useragent,:time)";
		  $params = array(
		      ":to"=>$to,
		      ":header"=>$headers,
		      ":subject"=>$subject,
		      ":content"=>utf8_encode($body),
		      ":file"=>$attachmentFile,
		      ":ip"=>$_SERVER['REMOTE_ADDR'],
	  		  ":sent"=>$mailSent,
	  		  ":useragent"=>$_SERVER['HTTP_USER_AGENT'],
	  		  ":time"=>date("d/m/Y H:i:s T(P)"),
		  );
		$DBobject->executeSQL($sql,$params);
	}catch(Exception $e){}
	return $mailSent;
}

function sendMailV2($to,$from,$fromEmail,$subject,$body){
  global $DBobject;
  require_once 'database/safemail.php';
  define("DEFCALLBACKMAIL", "{$fromEmail}");
  define("DEFCALLBACKNAME", "");
  $final_msg = preparehtmlmail($body);
  
  $mailSent = 0;
  try{
    if(function_exists("SafeMail")){
      $sql = "SELECT email_id FROM tbl_email_copy WHERE email_ip = :ip AND email_created BETWEEN DATE_SUB(NOW(), INTERVAL 1 HOUR) AND NOW() LIMIT 5";
      $params = array(
          ":ip"=>$_SERVER['REMOTE_ADDR']
      );
      $res = $DBobject->executeSQL($sql,$params);
      if(count($res) < 5 ){
        $mailSent = SafeMail($to,$subject,$final_msg['headers'],$final_msg['multipart']);
      }else{
        $mailSent= -1;
      }
    }
  }catch(Exception $e){}

  try{
    $sql = "INSERT INTO tbl_email_copy (email_to, email_header, email_subject, email_content,email_ip,email_sent,email_sender_useragent,email_time) VALUES 
	      (:to,:header,:subject,:content,:ip,:sent,:useragent,:time)";
	  $params = array(
	      ":to"=>$to,
          ":header"=>$final_msg['headers'],
	      ":subject"=>$subject,
          ":content"=>$final_msg['multipart'],
	      ":ip"=>$_SERVER['REMOTE_ADDR'],
  		  ":sent"=>$mailSent,
  		  ":useragent"=>$_SERVER['HTTP_USER_AGENT'],
  		  ":time"=>date("d/m/Y H:i:s T(P)"),
	  );
	  $DBobject->executeSQL($sql,$params);
  }catch(Exception $e){}

  return $mailSent;
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
function createBulkMail($to_Array,$from,$fromEmail,$subject,$body){
	global $DBobject;
	require_once 'database/safemail.php';

	/* To send HTML mail, you can set the Content-type header. */
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

	/* additional headers */
	$headers .= "From: ". $from . " <".$fromEmail.">\r\n";
	try{
		foreach ($to_Array as $to){
			$sql = "INSERT INTO tbl_email_copy (email_to, email_header, email_subject, email_content,email_ip,email_sent) VALUES
		      (:to,:header,:subject,:content,:ip,:sent)";
			$params = array(
					":to"=>$to,
					":header"=>$headers,
					":subject"=>$subject,
					":content"=>utf8_encode($body),
					":ip"=>$_SERVER['REMOTE_ADDR'],
					":sent"=>-2
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
 * @param number $_limit
 * @return boolean
 */
function sendBulkMail($_limit = 100){
	global $DBobject;
	require_once 'database/safemail.php';

	try{
		if(function_exists("SafeMail")){
			$sql = "SELECT * FROM tbl_email_copy WHERE email_sent = '-2' OR email_sent = '0' ORDER BY email_created LIMIT $_limit";
			if($res = $DBobject->executeSQL($sql)){
				foreach ($res as $r){
					if(SafeMail($r['email_to'],$r['email_subject'],$r['email_content'],$r['email_header'])){
						$sql = "UPDATE tbl_email_copy SET email_sent = '1' WHERE email_id = :email_id";
						$DBobject->executeSQL($sql, array(":email_id"=>$r['email_id']));
					}
				}
			}
			return true;
		}
	}catch(Exception $e){ die("Error: $e"); }
	return false;
}


function preparehtmlmail($html) {

  preg_match_all('~<img.*?src=.([\/.a-z0-9:_-]+).*?>~si',$html,$matches);
  $i = 0;
  $paths = array();

  foreach ($matches[1] as $img) {
    $img_old = $img;

    if(strpos($img, "http://") == false) {
      $uri = parse_url($img);
      $paths[$i]['path'] = $_SERVER['DOCUMENT_ROOT'].$uri['path'];
      $content_id = md5($img);
      $html = str_replace($img_old,'cid:'.$content_id,$html);
      $paths[$i++]['cid'] = $content_id;
    }
  }

  $boundary = "--".md5(uniqid(time()));
  $headers .= "MIME-Version: 1.0\n";
  $headers .="Content-Type: multipart/mixed; boundary=\"$boundary\"\n";
  $headers .= "From: ".DEFCALLBACKNAME." <".DEFCALLBACKMAIL.">\n";
  $headers .= "Bcc: cmsemails@them.com.au\r\n";
  $multipart = '';
  $multipart .= "--$boundary\n";
  $kod = 'utf-8';
  $multipart .= "Content-Type: text/html; charset=$kod\n";
  $multipart .= "Content-Transfer-Encoding: Quot-Printed\n\n";
  $multipart .= "$html\n\n";

  foreach ($paths as $path) {
    if(file_exists($path['path']))
      $fp = fopen($path['path'],"r");
      if (!$fp)  {
        return false;
      }

    $imagetype = substr(strrchr($path['path'], '.' ),1);
    $file = fread($fp, filesize($path['path']));
    fclose($fp);

    $message_part = "";

    switch ($imagetype) {
      case 'png':
      case 'PNG':
            $message_part .= "Content-Type: image/png";
            break;
      case 'jpg':
      case 'jpeg':
      case 'JPG':
      case 'JPEG':
            $message_part .= "Content-Type: image/jpeg";
            break;
      case 'gif':
      case 'GIF':
            $message_part .= "Content-Type: image/gif";
            break;
    }

    $message_part .= "; file_name = \"$path\"\n";
    $message_part .= 'Content-ID: <'.$path['cid'].">\n";
    $message_part .= "Content-Transfer-Encoding: base64\n";
    $message_part .= "Content-Disposition: inline; filename = \"".basename($path['path'])."\"\n\n";
    $message_part .= chunk_split(base64_encode($file))."\n";
    $multipart .= "--$boundary\n".$message_part."\n";

  }
  $multipart .= "--$boundary--\n";
  return array('multipart' => $multipart, 'headers' => $headers);
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
			$str = htmlspecialchars(trim($str),ENT_QUOTES,'UTF-8',false);
			$str = str_replace("&lt;", "<", $str);
			$str = str_replace("&gt;", ">", $str);
			$str = str_replace("&nbsp;", " ", $str);
			$str = addslashes($str);
			break;
	}

	return $str;
}

function htmlclean( $str ){
  $type = strtolower(gettype($str));

  switch($type){
    case "string":
      $str = unclean($str);
      $str = strip_tags($str);
      $str = htmlspecialchars(trim($str),ENT_QUOTES,'UTF-8',false);
      $str = addslashes($str);
      break;

    case "array":
      foreach($str as $key => $val){
        $str[$key] = htmlclean($val);
      }
      break;
    default:
      $str = unclean($str);
      $str = strip_tags($str);
      $str = htmlspecialchars(trim($str),ENT_QUOTES,'UTF-8',false);
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
	if($_SESSION[$name . "-token"] && !empty($_SESSION[$name . "-token"])){
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
	$token = sha1($_SERVER['REMOTE_ADDR'].md5(date("D M j G:i:s T Y")). md5(uniqid(mt_rand(rand(50,rand()),rand(500,23493244)), true)));
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

/*
 * Gets distance over a postcode and a place
 */
function postcode_dist($place1, $postcode2, $db) {
    //$sqlquery = "SELECT postcode_lat, postcode_lon FROM tbl_postcode WHERE postcode_lat <> 0 and postcode_lon <> 0 and postcode_postcode = '".$postcode1."'";
    //$place1 = $db->wrappedSqlGet($sqlquery);

	$sqlquery = "SELECT postcode_lat, postcode_lon FROM tbl_postcode WHERE postcode_lat <> 0 and postcode_lon <> 0 and postcode_postcode = '".$postcode2."'";
    $place2 = $db->wrappedSqlGet($sqlquery);

    if($place1 && $place2 && count($place1)>0 && count($place2)>0){
        foreach($place1 as $p1){
            foreach($place2 as $p2){
                $lat1 = $p1['postcode_lat'];
                $lon1 = $p1['postcode_lon'];

                $lat2 = $p2['postcode_lat'];
                $lon2 = $p2['postcode_lon'];

                $td = calc_dist($lat1, $lon1, $lat2, $lon2);
                if($dist && $td < $dist){
                    $dist = $td;
                }else{
                    $dist = $td;
                }
            }
        }
    }

	if (is_numeric($dist)) {
		return $dist;
	}
	else
	{
		return "99999999999";
	}
}

/*
 * Gets distance over a postcode and a place
 */
function nz_postcode_dist($postcode1, $postcode2, $db) {
    $sqlquery = "SELECT nzpostcode_lat, nzpostcode_lon FROM tbl_nzpostcode WHERE nzpostcode_lat <> 0 and nzpostcode_lon <> 0 and nzpostcode_postcode = '".$postcode1."'";
    $place1 = $db->wrappedSqlGet($sqlquery);

	$sqlquery = "SELECT nzpostcode_lat, nzpostcode_lon FROM tbl_nzpostcode WHERE nzpostcode_lat <> 0 and nzpostcode_lon <> 0 and nzpostcode_postcode = '".$postcode2."'";
    $place2 = $db->wrappedSqlGet($sqlquery);

    if($place1 && $place2 && count($place1)>0 && count($place2)>0){
        foreach($place1 as $p1){
            foreach($place2 as $p2){
                $lat1 = $p1['nzpostcode_lat'];
                $lon1 = $p1['nzpostcode_lon'];

                $lat2 = $p2['nzpostcode_lat'];
                $lon2 = $p2['nzpostcode_lon'];

                $td = calc_dist($lat1, $lon1, $lat2, $lon2);
                if($dist && $td < $dist){
                    $dist = $td;
                }else{
                    $dist = $td;
                }
            }
        }
    }

	if (is_numeric($dist)) {
		return $dist;
	}
	else
	{
		return "99999999999";
	}
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

	$sql = "SELECT admin_parent_id, admin_level FROM tbl_admin WHERE admin_deleted IS NULL AND admin_id = :id";
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
	$sql = "SELECT admin_id FROM tbl_admin WHERE admin_deleted IS NULL AND admin_parent_id = :id";
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
		$sql = "SELECT user_password FROM tbl_user WHERE user_deleted IS NULL AND user_id = :id";
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
				$sql = "SELECT * FROM tbl_user WHERE user_deleted IS NULL AND user_password = :user_password";
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


function sendGAEvent($_tid,$_category,$_action,$_label="",$_value=0){
  if(empty($_tid) || empty($_category) || empty($_action)) return false;

  //Submit Google Event
  // Standard params
  $v = 1;
  $tid = $_tid; // Put your own Analytics ID in here
  $cid = gaParseCookie();

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
  $dp = !empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"/API/";
  $p = !empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"/API/";

  // Send Transaction hit
  $data = array(
      'v' => $v,
      'tid' => $tid,
      'cid' => $cid,
      't' => 'event',
      'ec' => $ec,
      'ea' => $ea,
      'el' => $el,
      'ev' => $ev,
      'dh' => $dh,
      'dp' => $dp,
      'p' => $p
  );
  gaFireHit($data);
}

// See https://developers.google.com/analytics/devguides/collection/protocol/v1/devguide
function gaFireHit( $data = null ) {
  if ( $data ) {
    $getString = 'https://ssl.google-analytics.com/collect';
    $getString .= '?payload_data&';
    $getString .= http_build_query($data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $getString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($ch);
    return true;
  }
  return false;
}

// Handle the parsing of the _ga cookie or setting it to a unique identifier
function gaParseCookie() {
  if (isset($_COOKIE['_ga'])) {
    list($version,$domainDepth, $cid1, $cid2) = split('[\.]', $_COOKIE["_ga"],4);
    $contents = array('version' => $version, 'domainDepth' => $domainDepth, 'cid' => $cid1.'.'.$cid2);
    $cid = $contents['cid'];
  }elseif (!empty($_POST["_ga"])) {
    list($version,$domainDepth, $cid1, $cid2) = split('[\.]', $_POST["_ga"],4);
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


<?php

function getPass($username,$pass){
	return sha1(md5(bin2hex(strrev(stripslashes($username)))) . md5(stripslashes(strtoupper($pass))));
}

function urlSafeString($str){
	$str = str_replace(' ','-',$str);
	$str = preg_replace("/[\s\W]+/", "-", $str);
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
       '/^([*+!.&#$¦\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i'
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
 *
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
 *
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


function genRandomString($length) {
	if(!$length){
		$length = 5;
	}
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
	$msg .= "<br/>$trace";
	sendMail("cmsemails@them.com.au", $CONFIG->attributes->company_name.'-Website', 'noreply@website.com.au', 'Error', $msg);
	//echo $msg;
	//die();
	header('Location: /404');
	die();
}

function sendMail($to,$from,$fromEmail,$subject,$body){
	global $DBobject;
	/* To send HTML mail, you can set the Content-type header. */
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

	/* additional headers */
	$headers .= "From: ". $from . " <".$fromEmail.">\r\n";
	$headers .= "Bcc: cmsemails@them.com.au\r\n";

	ini_set('sendmail_from', $fromEmail);
	try{
	  	$sql = "INSERT INTO tbl_email_copy (email_to, email_header, email_subject, email_content) VALUES ('".clean($to)."','".clean($headers)."','".clean($subject)."','".clean($body)."')";
	  	$DBobject->executeSQL($sql);
	}catch(Exception $e){}
	$mailSent = mail($to,$subject,$body,$headers);
	ini_restore('sendmail_from');

	return $mailSent;
}

function sendMailV2($to,$from,$fromEmail,$subject,$body){
	global $DBobject;
    define("DEFCALLBACKMAIL", "{$fromEmail}");
    define("DEFCALLBACKNAME", "MedicAlert Foundation");
    $final_msg = preparehtmlmail($body, $boundary);

    ini_set('sendmail_from', $fromEmail);
	try{
	  	$sql = "INSERT INTO tbl_email_copy (email_to, email_header, email_subject, email_content) VALUES ('".clean($to)."','".clean($final_msg['headers'])."','".clean($subject)."','".clean($final_msg['multipart'])."')";
	  	$DBobject->executeSQL($sql);
	}catch(Exception $e){}
    $mailSent = mail($to,$subject,$final_msg['headers'],$final_msg['multipart']); 
    ini_restore('sendmail_from');

    return $mailSent;
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
			$str = htmlentities(trim($str),ENT_QUOTES,'UTF-8',false);
			$str = str_replace("&lt;", "<", $str);
			$str = str_replace("&gt;", ">", $str);
			$str = str_replace("&nbsp;", " ", $str);
			$str = addslashes($str);
			break;
	}

	return $str;
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

function getToken(){
	if($_SESSION["formToken"] && !empty($_SESSION["formToken"])){
		return $_SESSION["formToken"];
	}else{
		$_SESSION["formToken"] = generatetoken();
		return $_SESSION["formToken"];
	}
}

function generatetoken(){
	$token = sha1($_SERVER['REMOTE_ADDR'].md5(date("D M j G:i:s T Y")). md5(uniqid(mt_rand(rand(50,rand()),rand(500,23493244)), true)));
	return $token;
}

function checkToken($token){
	if($_SESSION["formToken"] == $token && !empty($token)){
		$isValid = true;
	}else{
		$isValid = false;
	}
	return $isValid;
}

function insertToken(){
	$this_buf='';
	$this_buf='<input type="hidden" name="formToken" id="formToken" value="'.getToken().'" >';
	return $this_buf;
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
	 * Enter description here ...
	 * @return unknown
	 */
	function LinkedFiles($misc, $id){
		if( $id != '' && $misc != ''){
			$linkfiles_obj = new filelink_class($misc, $id);
			$var	=	$linkfiles_obj->DisplayFiles();
		}
		return  $var;
	}
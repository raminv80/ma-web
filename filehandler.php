<?php 
////////////////////
// REQUEST HANDLER
////////////////////
session_start();
$res_level = array("(AR)"=>array("user"=>"admin"),"(MR)"=>array("user"=>array("public"=>"store_user")));

/* get all of the required data from the HTTP request */
$document_root    = $_SERVER['DOCUMENT_ROOT'];
$requested_uri    = parse_url(urldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH); //URL
$requested_file   = urlencode(basename($requested_uri));
$source_file      = $document_root.$requested_uri; // Full path to source file

//CHECK FILE EXISTS 
if(!file_exists($source_file)){
//   header("HTTP/1.0 404 Not Found"); //PHP ErrorDocument
//   header("Status: 404 Not Found"); //FastCGI
  header("location: /404");
  die();
}

//CHECK USER PERMISSIONS
foreach($res_level as $ck => $f){
  if(strpos($requested_uri, $ck) !== false){
    if(check_session($f,$_SESSION)){
      break 1;
    }else{
//       header("HTTP/1.0 403 Forbidden "); //PHP ErrorDocument
//       header("Status: 403 Forbidden "); //FastCGI
      header("location: /403");
      die();
    }
  }
}
$ct = "application/octet-stream";
try {
	$finfo = new finfo(FILEINFO_MIME);
	$type  = $finfo->file($source_file);
	if(!empty($type)){
		$ct = $type;
	}
} catch (Exception $e) {}

//HAS PERMISSIONS, RETURN FILE
header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
header("Cache-Control: public"); // needed for i.e.
header("Content-Type: ".$ct);
header("Content-Transfer-Encoding: Binary");
header("Content-Length:".filesize($source_file));
header("Content-Disposition: attachment; filename=$requested_file");
readfile($source_file);

function check_session($field, $_S){
  if(is_array($field)){
    foreach($field as $k => $v){
      if(empty($_S[$k])){
        return false;
      }else{
        return check_session($v,$_S[$k]);
      }
    }
  }else{
    if(empty($_S[$field])){
      return false;
    }
  }
  return true;
}
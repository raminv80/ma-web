<?php
// Fatal Error Handler
// Registering shutdown function
register_shutdown_function("fatal_handler");
function fatal_handler() {
  // Getting last error
  global $CONFIG;
  $errno = error_get_last();
  if($errno['type'] === E_ERROR){
    $body = "<table><thead bgcolor='#c8c8c8'><th>Item</th><th>Description</th></thead><tbody>";
    $body .= "<tr valign='top'><td><b>Error</b></td><td><pre>{$errno['message']}</pre></td></tr>";
    $body .= "<tr valign='top'><td><b>Errno</b></td><td><pre>{$errno['type']}</pre></td></tr>";
    $body .= "<tr valign='top'><td><b>File</b></td><td>{$errno['file']}</td></tr>";
    $body .= "<tr valign='top'><td><b>Line</b></td><td>{$errno['line']}</td></tr>";
    $body .= '</tbody></table>';
    $body .= '<br />$_POST<br/>';
    $body .= print_r($_POST,true);
    $body .= '<br />$_SERVER<br/>';
    $body .= print_r($_SERVER,true);
    if($CONFIG->attributes()->staging == 'true' || $_SERVER['REMOTE_ADDR'] == '150.101.230.130'){
    	die($body);
    }
    $subject = "Fatal error";
    $to = "weberrors@them.com.au";
    $from = (string) $CONFIG->company->name;
    $from = empty($from)?"website":$from;
    $fromEmail = 'noreply@' . str_replace ( "www.", "", $_SERVER['SERVER_NAME'] );
    /* To send HTML mail, you can set the Content-type header. */
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $headers .= "X-Priority: 3\r\n";
    $headers .= "X-Mailer: PHP". phpversion() ."\r\n";
    /* additional headers */
    $headers .= "Reply-To: ". $from . " <".$fromEmail.">\r\n";
    $headers .= "Return-Path: ". $from . " <".$fromEmail.">\r\n";
    $headers .= "From: ". $from . " <".$fromEmail.">\r\n";
    $headers .= "Bcc: medicalert.org.au@gmail.com\r\n";
    try{
    	$filename = $_SERVER['DOCUMENT_ROOT'].'/fatalerror'. date("Y-m-d-H") .'.txt';
    	if(!file_exists($filename)){
    		file_put_contents($filename, $body);
    		mail($to,$subject,$body,$headers, "-f$fromEmail");
    	}
			
    }catch(Exception $e){}
    
    $_SESSION['error'] = 'We had trouble saving your entry. Please review your entry and try again. If this continues please contact us.';
    header('location: /503.html');
    die('@ 503 Service Temporarily Unavailable');
  }
}
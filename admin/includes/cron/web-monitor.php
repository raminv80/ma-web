<?php
/**
 * WEBSITE/URL MONITORING SCRIPT WITH EMAIL NOTIFICATION - USING FILE LOGS
 */

// --- GENERAL SETTINGS ---
$websiteURL = "https://api.medicalert.org.au";
$websiteName = "api.medicalert.org.au (MS)";
$logFolderPath = $_SERVER['DOCUMENT_ROOT']. "/web-monitor/";

// --- EMAIL SETTINGS ---
$to = "apolo@them.com.au, medicalert.org.au@gmail.com";
$from = $_SERVER['HTTP_HOST'];
$fromEmail = "donotreply@medicalert.org.au";


// --- START OF MONITORING SCRIPT ---

// Verify that the folder exists 
make_path($logFolderPath . 'something');

// Ping the website/URL
$status = verify_url($websiteURL) ? 'UP' : 'DOWN';

try{
  // set default time zone to Adelaide time
  date_default_timezone_set('Australia/Adelaide');
  
  // Construct a new file/log every month 
  $filename = $logFolderPath . 'ping_log_'. date("Y-m");
  // Log record
  $newLine = $status . ' | '. date('Y-m-d h:i:s A') . ' | '. time();
  
  // Get the last status
  $lastStatus = '';
  if(file_exists($filename)){
    $lastLineStr = tailCustom($filename);
    $lastLineArr = explode(' | ', $lastLineStr);
    $lastStatus = $lastLineArr[0];
  }
  
  // Create/edit ping log
  file_put_contents($filename, $newLine . PHP_EOL, FILE_APPEND | LOCK_EX);
  
  // Compare current and last status and send email notification 
  if(!empty($lastStatus) && $status != $lastStatus){

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $headers .= "X-Priority: 3\r\n";
    $headers .= "X-Mailer: PHP". phpversion() ."\r\n";
    $headers .= "Reply-To: ". $from . " <".$fromEmail.">\r\n";
    $headers .= "Return-Path: ". $from . " <".$fromEmail.">\r\n";
    $headers .= "From: ". $from . " <".$fromEmail.">\r\n";
    
    $subject = "{$websiteName} is {$status}";
    
    $body = "{$websiteURL} is currently {$newLine}.";
    
    $mailResponse = mail($to, $subject , $body, $headers, "-f$fromEmail");
    
    // Create/edit mail log
    $filename = $logFolderPath . 'mail_log_'. date("Y-m");
    file_put_contents($filename, "{$mailResponse} | $to | {$newLine}" . PHP_EOL, FILE_APPEND | LOCK_EX);
  }
   
}catch(Exception $e){
  // Create/edit error log
  $filename = $logFolderPath . 'error_log';
  file_put_contents($filename, date('Y-m-d h:i:s A') . " {$e}" . PHP_EOL, FILE_APPEND | LOCK_EX);
}

die($status);
// --- END OF MONITORING SCRIPT ---


// --- FUNCTIONS ---
/**
 Make a nested path, creating directories down the path 
 */
function make_path($path) {
  $dir = pathinfo(rtrim($path, '/'),PATHINFO_DIRNAME);

  if(is_dir($dir)){
    return true;
  }else{
    if(make_path($dir)){
      if(mkdir($dir)){
        chmod($dir, 0755);
        return true;
      }
    }
  }
  return false;
}


/**
 cURL request to check a website/url 
 */
function verify_url($url){

  $ch = curl_init ($url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_TIMEOUT, 55);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $raw = curl_exec($ch);
  // Check HTTP status code
  $response = false;
  $error = curl_errno($ch);

  if(!$error) {
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    switch ($http_code) {
      case 200:  # OK
      case 301:  # Moved Permanently
      case 302:  # Found
        $response = true;
        break;
      default:
        $response = false;
    }
  }
  curl_close($ch);
  return $response;
}


/**
 * Slightly modified version of http://www.geekality.net/2011/05/28/php-tail-tackling-large-files/
 * @author Torleif Berger, Lorenzo Stanco
 * @link http://stackoverflow.com/a/15025877/995958
 * @license http://creativecommons.org/licenses/by/3.0/
 */
function tailCustom($filepath, $lines = 1, $adaptive = true) {
  // Open file
  $f = @fopen($filepath, "rb");
  if ($f === false) return false;
  // Sets buffer size, according to the number of lines to retrieve.
  // This gives a performance boost when reading a few lines from the file.
  if (!$adaptive) $buffer = 4096;
  else $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));
  // Jump to last character
  fseek($f, -1, SEEK_END);
  // Read it and adjust line number if necessary
  // (Otherwise the result would be wrong if file doesn't end with a blank line)
  if (fread($f, 1) != "\n") $lines -= 1;

  // Start reading
  $output = '';
  $chunk = '';
  // While we would like more
  while (ftell($f) > 0 && $lines >= 0) {
    // Figure out how far back we should jump
    $seek = min(ftell($f), $buffer);
    // Do the jump (backwards, relative to where we are)
    fseek($f, -$seek, SEEK_CUR);
    // Read a chunk and prepend it to our output
    $output = ($chunk = fread($f, $seek)) . $output;
    // Jump back to where we started reading
    fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
    // Decrease our line counter
    $lines -= substr_count($chunk, "\n");
  }
  // While we have too many lines
  // (Because of buffer size we might have read too many)
  while ($lines++ < 0) {
    // Find first newline and remove all text before that
    $output = substr($output, strpos($output, "\n") + 1);
  }
  // Close file and return
  fclose($f);
  return trim($output);
}

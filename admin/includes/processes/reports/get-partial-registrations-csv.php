<?php
$referer = parse_url($_SERVER['HTTP_REFERER']);
if($referer['host'] != $_SERVER['HTTP_HOST']){
  header('HTTP/1.0 403 Forbidden');
  die();
}

set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once 'includes/functions/functions.php';

global $DBobject;

if(empty($_SESSION['user']['admin']['id'])) die('Restricted area');

date_default_timezone_set('Australia/Adelaide');

$wheresql = '';
$params = array();

// INIT DATES
$startDate = date('Y-m-d', strtotime('-1 months'));
if(!empty($_POST['start'])){
  $startDate = date_format(date_create_from_format('d/m/Y', $_POST['start']), 'Y-m-d');
}
$wheresql .= " AND DATE(usertemp_created) >= :startdate";
$params['startdate'] = $startDate;

$endDate = date('Y-m-d');
if(!empty($_POST['end'])){
  $endDate = date_format(date_create_from_format('d/m/Y', $_POST['end']), 'Y-m-d');
}
$wheresql .= " AND DATE(usertemp_created) <= :enddate";
$params['enddate'] = $endDate; 


$result = array();

$sql = "SELECT usertemp_gname AS 'First name', usertemp_surname AS 'Surname',  usertemp_email AS 'Email', 
   usertemp_mobile AS 'Mobile', usertemp_home_phone as 'Home phone', usertemp_work_phone as 'Work phone', usertemp_gender AS 'Gender',  usertemp_address AS 'Address',
   usertemp_suburb AS 'Suburb', usertemp_state AS 'State',  usertemp_postcode AS 'Postcode',
   usertemp_heardabout AS 'How did you hear about us?', usertemp_created AS 'Created'
  FROM tbl_usertemp 
  WHERE usertemp_deleted IS NULL AND (usertemp_payment_id IS NULL OR usertemp_payment_id = 0) {$wheresql} 
  ORDER BY usertemp_created DESC";
if($res = $DBobject->wrappedSql($sql, $params)){
  $result = $res;
}

$res = unclean($result);
$csv = AssociativeArrayToCSV($res); 

$filename = "partial_registration_{$startDate}_{$endDate}.csv";
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: " . strlen($csv));
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=" . $filename);
echo $csv;
die();


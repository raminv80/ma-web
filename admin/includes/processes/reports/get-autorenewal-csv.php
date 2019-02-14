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
$wheresql .= " AND DATE(autorenew_created) >= :startdate";
$params['startdate'] = $startDate;

$endDate = date('Y-m-d');
if(!empty($_POST['end'])){
  $endDate = date_format(date_create_from_format('d/m/Y', $_POST['end']), 'Y-m-d');
}
$wheresql .= " AND DATE(autorenew_created) <= :enddate";
$params['enddate'] = $endDate; 


$result = array();

$sql = "SELECT autorenew_user_id AS 'Membership ID', autorenew_bank_customer_id AS 'Payway ID', autorenew_method AS 'Payment Method', autorenew_created AS 'Created' FROM tbl_autorenew WHERE autorenew_deleted IS NULL {$wheresql} ORDER BY autorenew_created DESC";
if($res = $DBobject->wrappedSql($sql, $params)){
  $result = $res;
}

$res = unclean($result);
$csv = AssociativeArrayToCSV($res); 

$filename = "autorenewal_{$startDate}_{$endDate}.csv";
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: " . strlen($csv));
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=" . $filename);
echo $csv;
die();


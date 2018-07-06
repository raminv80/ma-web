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
$wheresql .= " AND DATE(employee_wellbeing_created) >= :startdate";
$params['startdate'] = $startDate;

$endDate = date('Y-m-d');
if(!empty($_POST['end'])){
  $endDate = date_format(date_create_from_format('d/m/Y', $_POST['end']), 'Y-m-d');
}
$wheresql .= " AND DATE(employee_wellbeing_created) <= :enddate";
$params['enddate'] = $endDate; 


$result = array();

$sql = "SELECT employee_wellbeing_name AS 'Full name', employee_wellbeing_email AS 'Email',
  employee_wellbeing_phone AS 'Phone', employee_wellbeing_company AS 'Company name',  employee_wellbeing_created AS 'Date' 
  FROM tbl_employee_wellbeing 
  WHERE employee_wellbeing_deleted IS NULL {$wheresql} 
  ORDER BY employee_wellbeing_created DESC";
if($res = $DBobject->wrappedSql($sql, $params)){
  $result = $res;
}

$res = unclean($result);
$csv = AssociativeArrayToCSV($res); 

$filename = "employee_wellbeing_{$startDate}_{$endDate}.csv";
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: " . strlen($csv));
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=" . $filename);
echo $csv;
die();


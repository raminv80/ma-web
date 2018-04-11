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
$wheresql .= " AND DATE(member_cancellation_request_created) >= :startdate";
$params['startdate'] = $startDate;

$endDate = date('Y-m-d');
if(!empty($_POST['end'])){
  $endDate = date_format(date_create_from_format('d/m/Y', $_POST['end']), 'Y-m-d');
}
$wheresql .= " AND DATE(member_cancellation_request_created) <= :enddate";
$params['enddate'] = $endDate; 


$result = array();

$sql = "SELECT member_cancellation_request_membership_number as 'Membership #', member_cancellation_request_name as 'Name', member_cancellation_request_email as 'Email', 
  member_cancellation_request_phone as 'Phone', member_cancellation_request_reason as 'Reason', 
  member_cancellation_request_requested_by as 'Requested by', member_cancellation_request_requested_by_phone as 'Requested by - Phone', 
  member_cancellation_request_relation_to_member as 'Relation to member'
  FROM tbl_member_cancellation_request 
  WHERE member_cancellation_request_deleted IS NULL {$wheresql} 
  ORDER BY member_cancellation_request_created DESC";
if($res = $DBobject->wrappedSql($sql, $params)){
  $result = $res;
}

$res = unclean($result);
$csv = AssociativeArrayToCSV($res); 

$filename = "membership_cancellation_requests_{$startDate}_{$endDate}.csv";
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: " . strlen($csv));
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=" . $filename);
echo $csv;
die();


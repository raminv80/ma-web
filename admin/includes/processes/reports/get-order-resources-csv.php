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
$wheresql .= " AND DATE(orderresource_created) >= :startdate";
$params['startdate'] = $startDate;

$endDate = date('Y-m-d');
if(!empty($_POST['end'])){
  $endDate = date_format(date_create_from_format('d/m/Y', $_POST['end']), 'Y-m-d');
}
$wheresql .= " AND DATE(orderresource_created) <= :enddate";
$params['enddate'] = $endDate; 


$result = array();

$sql = "SELECT orderresource_form_name AS 'Source', orderresource_fname AS 'First name', orderresource_lname AS 'Surname', orderresource_job_title AS 'Job title', 
  orderresource_company AS 'Company name', orderresource_department AS 'Department', orderresource_address AS 'Postal address', 
  orderresource_suburb AS 'Suburb', orderresource_state AS 'State', orderresource_postcode AS 'Postcode', orderresource_phone AS 'Phone',  
  orderresource_email AS 'Email', orderresource_category AS 'Category', IF(orderresource_pack_required = 1, 'Yes', 'No') AS 'Need a resource pack',  
  orderresource_catalogue AS 'catalogues', orderresource_a3poster AS 'A3 posters', orderresource_stand AS 'Flyer stand and bracelet',  orderresource_created AS 'Created' 
  FROM tbl_orderresource 
  WHERE orderresource_deleted IS NULL {$wheresql} 
  ORDER BY orderresource_created DESC";
if($res = $DBobject->wrappedSql($sql, $params)){
  $result = $res;
}

$res = unclean($result);
$csv = AssociativeArrayToCSV($res); 

$filename = "order_resources_{$startDate}_{$endDate}.csv";
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: " . strlen($csv));
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=" . $filename);
echo $csv;
die();


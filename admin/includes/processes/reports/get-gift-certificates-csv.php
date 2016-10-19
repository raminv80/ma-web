<?php
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
$wheresql .= " AND DATE(voucher_created) >= :startdate";
$params['startdate'] = $startDate;

$endDate = date('Y-m-d');
if(!empty($_POST['end'])){
  $endDate = date_format(date_create_from_format('d/m/Y', $_POST['end']), 'Y-m-d');
}
$wheresql .= " AND DATE(voucher_created) <= :enddate";
$params['enddate'] = $endDate; 


$result = array();

$sql = "SELECT payment_transaction_no AS 'Order ID', voucher_code AS 'Code', voucher_amount AS 'Amount', voucher_name AS 'Sender name', voucher_email AS 'Sender email', voucher_recipientname AS 'Recipient name', 
  voucher_recipientemail AS 'Recipient email', voucher_message AS 'Message', voucher_anonymous AS 'Anonymous', voucher_created AS 'Created', voucher_start_date AS 'Start date', voucher_end_date AS 'End date', voucher_redeemed AS 'Redeemed' 
  FROM tbl_voucher LEFT JOIN tbl_payment ON payment_id = voucher_payment_id
  WHERE voucher_deleted IS NULL {$wheresql} ORDER BY voucher_created DESC";
if($res = $DBobject->wrappedSql($sql, $params)){
  $result = $res;
}

$res = unclean($result);
$csv = AssociativeArrayToCSV($res); 

$filename = "gift-certificates_{$startDate}_{$endDate}.csv";
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: " . strlen($csv));
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=" . $filename);
echo $csv;
die();


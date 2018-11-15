<?php
die('competition enteries report is disabled');
$referer = parse_url($_SERVER['HTTP_REFERER']);
if($referer['host'] != $_SERVER['HTTP_HOST']){
  header('HTTP/1.0 403 Forbidden');
  die();
}

set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject;


if (!empty($_POST['competition_reference_id'])) {
	
	$sql = "SELECT l.listing_name as `Competition Name`, competition_name as `Name`, competition_email as `Email`, competition_phone as `Phone`, competition_membership_number as `Membership Number`, competition_postcode as `Postcode`, competition_entry as `Entry`, competition_heardabout as `Hear About`, competition_created as `Date` FROM tbl_competition AS c LEFT JOIN tbl_listing AS l ON l.listing_object_id = c.competition_reference_id WHERE competition_deleted IS NULL AND competition_reference_id = :listing_object_id";
	$res = $DBobject->wrappedSql($sql, array(':listing_object_id'=>$_POST['competition_reference_id']));

	$csv = AssociativeArrayToCSV($res);
				
	$filename='Competition_Entries_'.$_POST['competition_reference_id']. '_'. '' .'.csv';
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-Length: " . strlen($csv));
	header("Content-type: text/x-csv");
	header("Content-Disposition: attachment; filename=".$filename);
    echo $csv;
}


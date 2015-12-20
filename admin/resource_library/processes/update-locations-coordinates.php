<?php
ini_set('display_errors',1);
ini_set('error_reporting', E_ALL ^ E_NOTICE ^ E_WARNING);
set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
include_once 'admin/includes/functions/admin-functions.php';
global $DBobject;

if($_SERVER['REMOTE_ADDR'] != '150.101.230.130') die('Restricted area');


$ssql = "SELECT * FROM tbl_location WHERE location_deleted IS NULL AND (location_latitude IS NULL OR location_latitude = '' OR location_longitude IS NULL OR location_longitude = '') ";
$res = $DBobject->executeSQL($ssql); 
foreach($res as $r){
	$coordinates = geocode("{$r['location_street']}, {$r['location_suburb']}, {$r['location_state']}, {$r['location_postcode']}");
	if(empty($coordinates)) continue;

  $sql = "UPDATE tbl_location SET location_latitude = :location_latitude, location_longitude = :location_longitude, location_modified = NOW()
    				WHERE location_id = :location_id";
  $params = array(
        "location_id"=>$r['location_id'],
        "location_latitude"=>$coordinates['lat'],
        "location_longitude"=>$coordinates['lng']
  );
  if($DBobject->executeSQL($sql,$params)) echo $r['location_id'] . '<br>';
}

die('End');
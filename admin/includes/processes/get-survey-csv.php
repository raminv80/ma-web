<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once 'includes/functions/functions.php';

global $DBobject;

if(empty($_SESSION['user']['admin']['id'])) die('Restricted area');

date_default_timezone_set('Australia/Adelaide');

$wheresql = '';
$params = array();

// INIT DATES
$endtDate = date('Y-m-d');

/* $startDate = date('Y-m-d', strtotime('-1 months'));
if(!empty($_REQUEST['start'])){
  $startDate = date('Y-m-d', strtotime($_REQUEST['start']));
}
$wheresql .= " AND DATE(survey_created) >= :startdate";
$params['startdate'] = $startDate;

$endtDate = date('Y-m-d');
if(!empty($_REQUEST['end'])){
  $endDate = date('Y-m-d', strtotime($_REQUEST['end']));
}
$wheresql .= " AND DATE(survey_created) <= :enddate";
$params['enddate'] = $endDate; */



$result = array();

$sql = "SELECT * FROM tbl_question WHERE question_deleted IS NULL ORDER BY question_type_id, question_order";
if($res = $DBobject->wrappedSql($sql)){
  //$result[0]['A'] = "ID";
  $result[0]['A'] = "Date-time";
  $result[0]['B'] = "Membership id";
  $result[0]['C'] = "Email";
  
  $header_ids = array();
  
  foreach($res as $headerres){
    $result[0][$headerres['question_id']] = $headerres['question_question'];
    array_push($header_ids, $headerres['question_id']);
  }
  
  $surveysql = "SELECT * FROM tbl_survey LEFT JOIN tbl_surveytoken ON surveytoken_id = survey_surveytoken_id 
    WHERE surveytoken_deleted IS NULL {$wheresql} ORDER BY surveytoken_id";
  if($surveyres = $DBobject->wrappedSql($surveysql, $params)){
    
    foreach($surveyres as $sur){
      //$result[$sur['survey_surveytoken_id']]['A'] = (empty($result[$sur['survey_surveytoken_id']]['A']))? $sur['survey_surveytoken_id'] : $result[$sur['survey_surveytoken_id']]['A'];
      $result[$sur['survey_surveytoken_id']]['A'] = empty($result[$sur['survey_surveytoken_id']]['A']) ? $sur['surveytoken_modified'] : $result[$sur['survey_surveytoken_id']]['A'];
      $result[$sur['survey_surveytoken_id']]['B'] = empty($result[$sur['survey_surveytoken_id']]['B']) ? $sur['surveytoken_user_id'] : $result[$sur['survey_surveytoken_id']]['B'];
      $result[$sur['survey_surveytoken_id']]['C'] = empty($result[$sur['survey_surveytoken_id']]['C']) ? $sur['surveytoken_useremail'] : $result[$sur['survey_surveytoken_id']]['C'];
      
      foreach($header_ids as $hid){
        if(!isset($result[$sur['survey_surveytoken_id']][$hid])){
          $result[$sur['survey_surveytoken_id']][$hid] = "";
        }
      }
      $result[$sur['survey_surveytoken_id']][$sur['survey_question_id']] = $sur['survey_answer'];
    }
  }
}

$res = unclean($result);
//$csv = AssociativeArrayToCSV($res); //with header
$csv = arrayToCSV($res); //without header

$filename = "survey_{$startDate}_{$endtDate}.csv";
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: " . strlen($csv));
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=" . $filename);
echo $csv;
die();


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
$wheresql .= " AND DATE(survey_created) >= :startdate";
$params['startdate'] = $startDate;

$endDate = date('Y-m-d');
if(!empty($_POST['end'])){
  $endDate = date_format(date_create_from_format('d/m/Y', $_POST['end']), 'Y-m-d');
}
$wheresql .= " AND DATE(survey_created) <= :enddate";
$params['enddate'] = $endDate; 



$result = array();

$sql = "SELECT * FROM tbl_question WHERE question_deleted IS NULL ORDER BY question_type_id, question_order";
if($res = $DBobject->wrappedSql($sql)){
  //$result[0]['A'] = "ID";
  $result[0]['A'] = "Created";
  $result[0]['B'] = "Submitted";
  $result[0]['C'] = "Membership ID";
  $result[0]['D'] = "Email";
  
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
      $result[$sur['survey_surveytoken_id']]['A'] = empty($result[$sur['survey_surveytoken_id']]['A']) ? $sur['surveytoken_created'] : $result[$sur['survey_surveytoken_id']]['A'];
      $result[$sur['survey_surveytoken_id']]['B'] = empty($result[$sur['survey_surveytoken_id']]['B']) ? $sur['surveytoken_modified'] : $result[$sur['survey_surveytoken_id']]['A'];
      $result[$sur['survey_surveytoken_id']]['C'] = empty($result[$sur['survey_surveytoken_id']]['C']) ? $sur['surveytoken_user_id'] : $result[$sur['survey_surveytoken_id']]['C'];
      $result[$sur['survey_surveytoken_id']]['D'] = empty($result[$sur['survey_surveytoken_id']]['D']) ? $sur['surveytoken_useremail'] : $result[$sur['survey_surveytoken_id']]['D'];
      
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

$filename = "survey_{$startDate}_{$endDate}.csv";
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: " . strlen($csv));
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=" . $filename);
echo $csv;
die();


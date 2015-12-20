<?php
session_start();
if((isset($_SESSION['user']['admin']) && !empty($_SESSION['user']['admin']) )){
  set_include_path($_SERVER['DOCUMENT_ROOT']);
  include_once 'admin/includes/functions/admin-functions.php';
  require_once 'admin/includes/createsend/csrest_lists.php';
  require_once 'admin/includes/createsend/csrest_segments.php';
  global $DBobject;
  
  $MySQL = "SELECT listing_object_id,listing_url FROM tbl_listing WHERE listing_type_id = '2' AND listing_deleted IS NULL AND listing_published = 1";
  $issuesList = $DBobject->executeSQL($MySQL);
  
  $wrap = new CS_REST_Lists('1ad04e1c384a79cacd9ca47e8c3b8f5c', '7d6ddc2467944f2a174afd5eb05040b4');
  //Retrieve Segments
  $segments = array();
  $result = $wrap->get_segments();
  if($result->was_successful()) {
    foreach($result->response as $s){
      $segments[$s->SegmentID] = $s;
    }
  } else {
    $_SESSION['error']='You do not have permission to submit this form. Please refresh the page and try again.';
    header("Location: {$_SERVER['HTTP_REFERER']}#error");
    die();
  }
  
  $options=array();
  $result = $wrap->get_custom_fields();
  if($result->was_successful()) {
    foreach($result->response as $f){
      if($f->FieldName !== "Issues"){ continue; }
      foreach($f->FieldOptions as $o){
        $options[] = $o;
      }
    }
  }
  
  $newOptions = array("All");$newSegments=array();
  foreach($issuesList as $i){
    if(!in_array($i['listing_url'], $options)){
      $newOptions[]=$i['listing_url'];
    }
    if(!key_exists($i['listing_url'], $segments)){
      $newSegments[]=$i;
    }
  }
  //Update Multiselect for Issues
  if(!empty($newOptions)){ $result = $wrap->update_field_options('[Issues]', $newOptions, true); }
  
  
  $wrap = new CS_REST_Segments(NULL, '7d6ddc2467944f2a174afd5eb05040b4');
  
  foreach($newSegments as $seg){
    $result = $wrap->create('1ad04e1c384a79cacd9ca47e8c3b8f5c', array(
        'Title' => $seg['listing_url'],
        'Rules' => array(
            array(
                'Subject' => '[Issues]',
                'Clauses' => array("EQUALS {$seg['listing_url']}")
            )
        )
    ));
  }
}
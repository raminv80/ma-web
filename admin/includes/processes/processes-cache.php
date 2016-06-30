<?php
session_start();
if((isset($_SESSION['user']['admin']) && !empty($_SESSION['user']['admin']) )){
  set_include_path ( $_SERVER ['DOCUMENT_ROOT'] );
  include_once 'admin/includes/functions/admin-functions.php';
  BuildCache();
}

function BuildCache() {
  global $DBobject;
  $sql[0] = "CREATE TABLE IF NOT EXISTS `cache_tbl_listing` (
		`cache_id` INT(11) NOT NULL AUTO_INCREMENT,
		`cache_record_id` INT(11) DEFAULT NULL,
		`cache_url` VARCHAR(255) DEFAULT NULL,
		`cache_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
		`cache_modified` DATETIME DEFAULT NULL,
		`cache_deleted` DATETIME DEFAULT NULL,
		PRIMARY KEY (`cache_id`)
		) ENGINE=MYISAM DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;";
  $DBobject->wrappedSql($sql[0]);

  $sql[3] = "TRUNCATE cache_tbl_listing;";
  $sql[2] = "INSERT INTO cache_tbl_listing (cache_record_id,cache_published,cache_url,cache_modified) VALUES  ";
  $params = array();
  $sql[1] = "SELECT listing_object_id AS id,listing_published FROM tbl_listing WHERE listing_deleted IS NULL";
  $res = $DBobject->wrappedSql($sql[1]);
  $n = 1;

  foreach($res as $row){
    $id = $row['id'];
    $url = "";
    $published = $row['listing_published'];
    if(!LocalBuildUrl($id,$url,$published)){
      continue;
    }
    $sql[2] .= " ( :id{$n}, :published{$n}, :title{$n}, now() ) ,";
    $params = array_merge($params,array(
        ":id{$n}"=>$id,
        ":published{$n}"=>$published,
        ":title{$n}"=>$url
    ));
    $n ++;
  }

  $sql[2] = trim(trim($sql[2]),',');
  $sql[2] .= ";";
  $DBobject->wrappedSql($sql[3]);
  $DBobject->wrappedSql($sql[2],$params);
}

// TODO: THIS NEEDS TO BE REVISED TO SUPPORT HAVING 2 URLS FOR THE SAME LISTING_OBJECT_ID IN THE EVENT THAT THE URL VALUE IS CHANGING.
function LocalBuildUrl($_id, &$url, $_PUBLISHED = 1) {
  global $DBobject;
  $sql = "SELECT * FROM tbl_listing WHERE listing_object_id = :id AND listing_published = :published AND listing_deleted IS NULL ORDER BY listing_modified DESC";
  $params = array(
      ":id"=>$_id,
      ":published"=>$_PUBLISHED
  );
  if($res = $DBobject->wrappedSql($sql,$params)){
    if(! empty($res[0]['listing_parent_id']) && $res[0]['listing_parent_id'] > 0 && $res[0]['listing_parent_id'] <> $_id){ // category_listing_id
      $url = $res[0]['listing_url'] . '/' . $url;
      return LocalBuildUrl($res[0]['listing_parent_id'],$url); // category_listing_id
    }else{
      $url = $res[0]['listing_url'] . '/' . $url;
      $url = ltrim(rtrim($url,'/'),'/');
      return true;
    }
  }else{
    return false;
  }
}
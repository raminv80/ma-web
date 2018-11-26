<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

try{
  $sql = "SELECT listing_name, listing_url, listing_content1, listing_content1, listing_content2, listing_image 
  FROM tbl_listing WHERE (listing_deleted IS NULL OR listing_deleted = '0000-00-00') 
  AND listing_published = 1 AND listing_type_id = 4 ORDER BY RAND() LIMIT 3";
  $res = $DBobject->wrappedSql($sql);
  $SMARTY->assign('testimonials', $res);
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

  

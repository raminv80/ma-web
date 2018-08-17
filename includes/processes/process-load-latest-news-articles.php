<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

if(!empty($SMARTY->getTemplateVars('listing_associate4'))){
  try{
    $newsCatId = $SMARTY->getTemplateVars('listing_associate4');
    $sql = "SELECT news_id, news_listing_id, news_start_date, tbl_listing.*, tbl.listing_url as parent_listing_url FROM tbl_news  
                        LEFT JOIN tbl_listing  ON news_listing_id = listing_id 
                        LEFT JOIN tbl_newscatlink tn ON news_listing_id = newscatlink_listing_id
                        LEFT JOIN tbl_listing tbl ON tbl_listing.listing_parent_id = tbl.listing_id 
                        WHERE newscatlink_cat_id = :news_cat_id AND newscatlink_deleted IS NULL AND 
                        tbl_listing.listing_published = 1 AND tbl_listing.listing_deleted IS NULL AND  
                        (tbl_listing.listing_schedule_start < NOW() OR tbl_listing.listing_schedule_start IS NULL)  AND
                        (tbl_listing.listing_schedule_end > NOW() OR tbl_listing.listing_schedule_end IS NULL)  
                        ORDER BY news_start_date DESC, news_id DESC LIMIT 3";
    $params = array('news_cat_id' => $newsCatId);
    $latest_news_articles = array();
    if($latest_news_articles = $DBobject->wrappedSql($sql, $params)){
      
    }
    $SMARTY->assign('latest_news_articles', $latest_news_articles);
  }catch(exceptionCart $e) {
    $SMARTY->assign('error', $e->getMessage());
  }
  
}
  

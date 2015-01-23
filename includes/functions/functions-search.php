<?php
function searchcms($str){
	global $SMARTY,$DBobject;
	$SMARTY->assign('term',$str);
	$count = 0;
	$tags = array();
	$results = array();
	if(empty($str)){
		$SMARTY->assign('count',$count);
		$SMARTY->assign('results',$results);
		return;
	}
	
	$_str = unclean($str);
	$_str = str_replace(" ", "%", $_str);
	$str = htmlclean($_str);
	
	/*$tags['pages']  = SearchPagesTags($str);
	$tags['news']  = SearchNewsTags($str);
	$tags['faq']  = SearchFAQTags($str);
	$tags['video']  = SearchVideoTags($str);
	
	$results['pages']  = array_merge($tags['pages'],SearchPages($str));
	$results['news']  = array_merge($tags['news'],SearchNews($str));
	$results['faq']  = array_merge($tags['faq'],SearchFAQ($str));
	$results['video']  = array_merge($tags['video'],SearchVideo($str));*/
	
	
	$results['listing'] = SearchListing($str);
	$results['product'] = SearchProduct($str);
  $results['categories'] = SearchCategories($str);
  $results['stores'] = SearchStores($str);
	
	$SMARTY->assign('count',$count);
	$SMARTY->assign('results',$results);
}

function SearchListing($search){
	global  $CONFIG,$SMARTY,$DBobject;
	$data = array();
	
  //SEARCH TAGS
	$sql = "SELECT * FROM tbl_tag WHERE tag_value = :value AND tag_deleted IS NULL AND tag_object_id > 0 AND tag_object_table = 'tbl_listing'" ;
	if($res = $DBobject->wrappedSql($sql, array(":value"=>$search))){
	  $no = 0;
	  foreach ( $res as $t) {
	    $no++;
	    $pre = str_replace ( "tbl_", "", $t['tag_object_table'] );
	    $sql = "SELECT cache_url,tbl_listing.* FROM tbl_listing LEFT JOIN cache_tbl_listing ON listing_object_id = cache_record_id WHERE listing_id = :id AND listing_deleted IS NULL AND listing_published = 1 AND cache_published = 1 AND listing_type_id = 1";
	    if($res2 = $DBobject->wrappedSql($sql, array(":id"=>$t['tag_object_id']))){
	      $data ["{$res2[0]['cache_url']}"] = $res2[0];
	      $data["{$res2[0]['cache_url']}"]['tags'] = getTags('tbl_listing',$t['tag_object_id']);
        $sql = "SELECT * FROM tbl_gallery WHERE gallery_product_id = :id AND gallery_deleted IS NULL";
        $gallery = $DBobject->wrappedSql($sql, array(":id"=>$res2[0]['product_id']));
        $data["{$res2[0]['cache_url']}"]['gallery'] = $gallery;
	    }
	  }
	}
	
	if(count($data) > 0){
	  return $data;
	}
	
	
	//IF TAG RESULTS = 0
	
	$sql= "SELECT tbl_listing.listing_object_id,tbl_listing.listing_name,tbl_listing.listing_meta_description,cache_tbl_listing.cache_url,
		MATCH(tbl_listing.listing_name,
		tbl_listing.listing_content1,
		tbl_listing.listing_seo_title,
		tbl_listing.listing_meta_description,
		tbl_listing.listing_meta_words) AGAINST (:search) AS Relevance
		FROM tbl_listing LEFT JOIN cache_tbl_listing ON tbl_listing.listing_object_id = cache_tbl_listing.cache_record_id 
		WHERE tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_published = 1 AND cache_tbl_listing.cache_published = 1
			AND tbl_listing.listing_url != '404' AND tbl_listing.listing_url != 'search' AND tbl_listing.listing_type_id = 1 AND 
		MATCH(tbl_listing.listing_name,
		tbl_listing.listing_content1,
		tbl_listing.listing_seo_title,
		tbl_listing.listing_meta_description,
		tbl_listing.listing_meta_words) AGAINST(:search IN
		BOOLEAN MODE) HAVING Relevance > 0.2 ORDER
		BY Relevance DESC";
	$params = array(":search"=>$search);
	if ($res = $DBobject->wrappedSql($sql,$params) ) {
		foreach ($res as $r){
			$data ["{$r['cache_url']}"] = $r;
			$data ["{$r['cache_url']}"]['tags'] = getTags('tbl_listing',$r['listing_id']);
			$sql = "SELECT * FROM tbl_gallery WHERE gallery_product_id = :id AND gallery_deleted IS NULL";
			$gallery = $DBobject->wrappedSql($sql, array(":id"=>$r['product_id']));
		  if(!empty($gallery)){
			$data ["{$r['cache_url']}"]['gallery'] = $gallery;
			}
		}
	}
	return $data;
}

function SearchCategories($search){
  global  $CONFIG,$SMARTY,$DBobject;
  $data = array();

  //SEARCH TAGS
	$sql = "SELECT * FROM tbl_tag WHERE tag_value = :value AND tag_deleted IS NULL AND tag_object_id > 0 AND tag_object_table = 'tbl_listing'" ;
	if($res = $DBobject->wrappedSql($sql, array(":value"=>$search))){
	  $no = 0;
	  foreach ( $res as $t) {
	    $no++;
	    $pre = str_replace ( "tbl_", "", $t['tag_object_table'] );
	    $sql = "SELECT cache_url,tbl_listing.* FROM tbl_listing LEFT JOIN cache_tbl_listing ON listing_object_id = cache_record_id WHERE listing_id = :id AND listing_deleted IS NULL AND listing_published = 1 AND cache_published = 1 AND listing_type_id = 2";
	    if($res2 = $DBobject->wrappedSql($sql, array(":id"=>$t['tag_object_id']))){
	      $data ["{$res2[0]['cache_url']}"] = $res2[0];
	      $data["{$res2[0]['cache_url']}"]['tags'] = getTags('tbl_listing',$t['tag_object_id']);
        $sql = "SELECT * FROM tbl_gallery WHERE gallery_product_id = :id AND gallery_deleted IS NULL";
        $gallery = $DBobject->wrappedSql($sql, array(":id"=>$res2[0]['product_id']));
        $data["{$res2[0]['cache_url']}"]['gallery'] = $gallery;
	    }
	  }
	}
	
	if(count($data) > 0){
	  return $data;
	}
  
  //IF TAG RESULTS = 0
  
  $sql= "SELECT tbl_listing.listing_object_id,tbl_listing.listing_name,tbl_listing.listing_content1,cache_tbl_listing.cache_url,tbl_listing.listing_image,
		MATCH(tbl_listing.listing_name,
		tbl_listing.listing_content1,
		tbl_listing.listing_seo_title,
		tbl_listing.listing_meta_description,
		tbl_listing.listing_meta_words) AGAINST (:search) AS Relevance
		FROM tbl_listing LEFT JOIN cache_tbl_listing ON tbl_listing.listing_object_id = cache_tbl_listing.cache_record_id
		WHERE tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_published = 1 AND cache_tbl_listing.cache_published = 1
			AND tbl_listing.listing_url != '404' AND tbl_listing.listing_url != 'search' AND tbl_listing.listing_type_id = 2 AND
		MATCH(tbl_listing.listing_name,
		tbl_listing.listing_content1,
		tbl_listing.listing_seo_title,
		tbl_listing.listing_meta_description,
		tbl_listing.listing_meta_words) AGAINST(:search IN
		BOOLEAN MODE) HAVING Relevance > 0.2 ORDER
		BY Relevance DESC";
  $params = array(":search"=>$search);
  if ($res = $DBobject->wrappedSql($sql,$params) ) {
    foreach ($res as $r){
      $data ["{$r['cache_url']}"] = $r;
      $data ["{$r['cache_url']}"]['tags'] = getTags('tbl_listing',$r['listing_id']);
      $sql = "SELECT * FROM tbl_gallery WHERE gallery_product_id = :id AND gallery_deleted IS NULL";
      $gallery = $DBobject->wrappedSql($sql, array(":id"=>$r['product_id']));
      if(!empty($gallery)){
			$data ["{$r['cache_url']}"]['gallery'] = $gallery;
			}
    }
  }
  return $data;
}

function SearchStores($search){
  global  $CONFIG,$SMARTY,$DBobject;
  $data = array();

  //SEARCH TAGS
	$sql = "SELECT * FROM tbl_tag WHERE tag_value = :value AND tag_deleted IS NULL AND tag_object_id > 0 AND tag_object_table = 'tbl_listing'" ;
	if($res = $DBobject->wrappedSql($sql, array(":value"=>$search))){
	  $no = 0;
	  foreach ( $res as $t) {
	    $no++;
	    $pre = str_replace ( "tbl_", "", $t['tag_object_table'] );
	    $sql = "SELECT cache_url,tbl_listing.* FROM tbl_listing LEFT JOIN cache_tbl_listing ON listing_object_id = cache_record_id WHERE listing_id = :id AND listing_deleted IS NULL AND listing_published = 1 AND cache_published = 1 AND listing_type_id = 5";
	    if($res2 = $DBobject->wrappedSql($sql, array(":id"=>$t['tag_object_id']))){
	      $data ["{$res2[0]['cache_url']}"] = $res2[0];
	      $data["{$res2[0]['cache_url']}"]['tags'] = getTags('tbl_listing',$t['tag_object_id']);
        $sql = "SELECT * FROM tbl_gallery WHERE gallery_product_id = :id AND gallery_deleted IS NULL";
        $gallery = $DBobject->wrappedSql($sql, array(":id"=>$res2[0]['product_id']));
        $data["{$res2[0]['cache_url']}"]['gallery'] = $gallery;
	    }
	  }
	}
	
	if(count($data) > 0){
	  return $data;
	}
  
  
  //IF TAG RESULTS = 0
  
  $sql= "SELECT tbl_listing.listing_object_id,tbl_listing.listing_name,tbl_listing.listing_meta_description,cache_tbl_listing.cache_url,
		MATCH(tbl_listing.listing_name,
		tbl_listing.listing_content1,
		tbl_listing.listing_seo_title,
		tbl_listing.listing_meta_description,
		tbl_listing.listing_meta_words) AGAINST (:search) AS Relevance
		FROM tbl_listing LEFT JOIN cache_tbl_listing ON tbl_listing.listing_object_id = cache_tbl_listing.cache_record_id
		WHERE tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_published = 1 AND cache_tbl_listing.cache_published = 1
			AND tbl_listing.listing_url != '404' AND tbl_listing.listing_url != 'search' AND tbl_listing.listing_type_id = 5 AND
		MATCH(tbl_listing.listing_name,
		tbl_listing.listing_content1,
		tbl_listing.listing_seo_title,
		tbl_listing.listing_meta_description,
		tbl_listing.listing_meta_words) AGAINST(:search IN
		BOOLEAN MODE) HAVING Relevance > 0.2 ORDER
		BY Relevance DESC";
  $params = array(":search"=>$search);
  if ($res = $DBobject->wrappedSql($sql,$params) ) {
    foreach ($res as $r){
      $data ["{$r['cache_url']}"] = $r;
      $data ["{$r['cache_url']}"]['tags'] = getTags('tbl_listing',$r['listing_id']);
      $sql = "SELECT * FROM tbl_gallery WHERE gallery_product_id = :id AND gallery_deleted IS NULL";
      $gallery = $DBobject->wrappedSql($sql, array(":id"=>$r['product_id']));
      if(!empty($gallery)){
			$data ["{$r['cache_url']}"]['gallery'] = $gallery;
			}
    }
  }
  return $data;
}


function SearchProduct($search){
	global  $CONFIG,$SMARTY,$DBobject;
	$data = array();

	//SEARCH TAGS
	$sql = "SELECT * FROM tbl_tag WHERE tag_value = :value AND tag_deleted IS NULL AND tag_object_id > 0 AND tag_object_table = 'tbl_product'" ;
	if($res = $DBobject->wrappedSql($sql, array(":value"=>$search))){
	  $no = 0;
	  foreach ( $res as $t) {
	    $no++;
	    $pre = str_replace ( "tbl_", "", $t['tag_object_table'] );
	    $sql = "SELECT cache_url,tbl_product.* FROM tbl_product LEFT JOIN cache_tbl_product ON product_object_id = cache_record_id WHERE product_id = :id AND product_deleted IS NULL AND product_published = 1 AND cache_published = 1";
	    if($res2 = $DBobject->wrappedSql($sql, array(":id"=>$t['tag_object_id']))){
	      $data ["{$res2[0]['cache_url']}"] = $res2[0];
	      $data["{$res2[0]['cache_url']}"]['tags'] = getTags('tbl_product',$t['tag_object_id']);
        $sql = "SELECT * FROM tbl_gallery WHERE gallery_product_id = :id AND gallery_deleted IS NULL";
        $gallery = $DBobject->wrappedSql($sql, array(":id"=>$res2[0]['product_id']));
        $data["{$res2[0]['cache_url']}"]['gallery'] = $gallery;
	    }
	  }
	}
	
	if(count($data) > 0){
	  return $data;
	}
	
	
	//IF TAG RESULTS = 0
	$sql= "SELECT DISTINCT(cache_url), stbl.* FROM (
			SELECT cache_url, tbl_product.*, 
			MATCH(product_name,
				product_description,
				product_seo_title,
				product_meta_description,
				product_meta_words,
				product_group
			) AGAINST (:search) AS Relevance1,
			MATCH(attr_value_name ) AGAINST (:search) AS Relevance2
		FROM tbl_product 
			LEFT JOIN cache_tbl_product ON product_object_id = cache_record_id
			LEFT JOIN tbl_attribute ON product_id = attribute_product_id
			LEFT JOIN tbl_attr_value ON attribute_id = attr_value_attribute_id
		WHERE product_published = 1 AND product_deleted IS NULL AND attribute_deleted IS NULL AND attr_value_deleted IS NULL AND cache_published = 1 AND
			( MATCH(product_name,
				product_description,
				product_seo_title,
				product_meta_description,
				product_meta_words,
				product_group
			) AGAINST(:search IN BOOLEAN MODE) OR
			MATCH(attr_value_name ) AGAINST (:search IN BOOLEAN MODE) )
			HAVING Relevance1 > 0.2 OR Relevance2 > 0.2
			ORDER BY (Relevance1+Relevance2) DESC ) AS stbl";
	$params = array(":search"=>$search);
	
	if ($res = $DBobject->wrappedSql($sql,$params) ) {
		foreach ($res as $r){
			$data ["{$r['cache_url']}"] = $r;
			$data ["{$r['cache_url']}"]['tags'] = getTags('tbl_product',$r['product_id']);
			$sql = "SELECT * FROM tbl_gallery WHERE gallery_product_id = :id AND gallery_deleted IS NULL";
			$gallery = $DBobject->wrappedSql($sql, array(":id"=>$r['product_id']));
			if(!empty($gallery)){
			$data ["{$r['cache_url']}"]['gallery'] = $gallery;
			}
		}
	}
	return $data;
}

function getTags($table, $id){
	global  $DBobject;
	$sql = "SELECT tag_value FROM tbl_tag WHERE tag_object_table = :table AND tag_object_id = :id AND tag_deleted IS NULL";
	return $DBobject->wrappedSql($sql, array(":table"=>$table, ":id"=>$id));
}

//SEARCH TAGS
/* 
function SearchPageTags($str){9
	$sql = 'SELECT * FROM tbl_page WHERE page_tags LIKE "%'.$str.'%"';
	$res = $DBobject->wrappedsql($sql);
	if(is_array($res)){
		return $res;
	}else{
		return array();
	}
}

function SearchNewsTags($str){
	global $DBobject;	
	$sql = 'SELECT * FROM tbl_news WHERE news_tags LIKE "%'.$str.'%" ';
	$res = $DBobject->wrappedsql($sql);
	if(is_array($res)){
		return $res;
	}else{
		return array();
	}
}
function SearchFAQTags($str){
	global $DBobject;	
	$sql = 'SELECT * FROM tbl_faq WHERE faq_tags LIKE "%'.$str.'%" ';
	$res = $DBobject->wrappedsql($sql);
	if(is_array($res)){
		return $res;
	}else{
		return array();
	}
}
function SearchVideoTags($str){
	global $DBobject;	
	$sql = 'SELECT * FROM tbl_video WHERE video_tags LIKE "%'.$str.'%" ';
	$res = $DBobject->wrappedsql($sql);
	if(is_array($res)){
		return $res;
	}else{
		return array();
	}
}

//SEARCH NORMAL
function SearchPages($str){
	global $DBobject;	
	$sql = 'SELECT * FROM tbl_page LEFT JOIN tbl_link_page_field WHERE (page_seo_title LIKE "%'.$str.'%" OR page_metawords LIKE "%'.$str.'%" OR page_metadescription LIKE "%'.$str.'%"  OR field_content LIKE "%'.$str.'%" ) AND page_tags NOT LIKE "%'.$str.'%"';
	$res = $DBobject->wrappedsql($sql);
	if(is_array($res)){
		return $res;
	}else{
		return array();
	}
}
function SearchNews($str){
	global $DBobject;	
	$sql = 'SELECT * FROM tbl_news WHERE (news_title LIKE "%'.$str.'%" OR news_short_description LIKE "%'.$str.'%" OR news_long_description LIKE "%'.$str.'%") AND news_tags NOT LIKE "%'.$str.'%" ';
	$res = $DBobject->wrappedsql($sql);
	if(is_array($res)){
		return $res;
	}else{
		return array();
	}
}
function SearchFAQ($str){
	global $DBobject;	
	$sql = 'SELECT * FROM tbl_faq WHERE (faq_question LIKE "%'.$str.'%" OR faq_answer LIKE "%'.$str.'%") AND faq_tags NOT LIKE "%'.$str.'%" ';
	$res = $DBobject->wrappedsql($sql);
	if(is_array($res)){
		return $res;
	}else{
		return array();
	}
}
function SearchVideo($str){
	global $DBobject;	
	$sql = 'SELECT * FROM tbl_video WHERE (video_title LIKE "%'.$str.'%" OR video_description LIKE "%'.$str.'%") AND video_tags NOT LIKE "%'.$str.'%" ';
	$res = $DBobject->wrappedsql($sql);
	if(is_array($res)){
		return $res;
	}else{
		return array();
	}
} */
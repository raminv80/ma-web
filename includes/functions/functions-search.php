<?php

function SearchListing($search,$type=1){
	global  $CONFIG,$SMARTY,$DBobject;
	$data = array();
	$search = preg_replace("/[^\w+\-\s]/", " ", trim($search, '-'));
	
	
	//IF TAG RESULTS = 0
	
	$sql= "SELECT tbl_listing.*,cache_tbl_listing.cache_url,tbl_type.*,
		MATCH(tbl_listing.listing_name,
		tbl_listing.listing_content1,
		tbl_listing.listing_content2,
		tbl_listing.listing_content3,
		tbl_listing.listing_content4,
		tbl_listing.listing_content5,
		tbl_listing.listing_content6,
		tbl_listing.listing_seo_title,
		tbl_listing.listing_meta_description,
		tbl_listing.listing_meta_words) AGAINST (:search) AS Relevance
		FROM tbl_listing LEFT JOIN cache_tbl_listing ON tbl_listing.listing_object_id = cache_tbl_listing.cache_record_id
	  LEFT JOIN tbl_type ON listing_type_id = type_id  
		WHERE tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_published = 1 AND cache_tbl_listing.cache_published = 1
		AND (listing_noindex IS NULL OR listing_noindex != 1)	
	    AND tbl_listing.listing_url != '404' AND tbl_listing.listing_url != 'search' AND 
		MATCH(tbl_listing.listing_name,
		tbl_listing.listing_content1,
		tbl_listing.listing_content2,
		tbl_listing.listing_content3,
		tbl_listing.listing_content4,
		tbl_listing.listing_content5,
		tbl_listing.listing_content6,
		tbl_listing.listing_seo_title,
		tbl_listing.listing_meta_description,
		tbl_listing.listing_meta_words) AGAINST(:search IN
		BOOLEAN MODE) HAVING Relevance > 0.2 ORDER
		BY type_order ASC, Relevance DESC";
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
	
	if(count($data) > 0){
	  return $data;
	}
	
	$sql= "SELECT tbl_listing.*,cache_tbl_listing.cache_url,tbl_type.*
		FROM tbl_listing LEFT JOIN cache_tbl_listing ON tbl_listing.listing_object_id = cache_tbl_listing.cache_record_id
	  LEFT JOIN tbl_type ON listing_type_id = type_id
	  LEFT JOIN tbl_additional ON additional_listing_id = listing_id
		WHERE tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_published = 1 AND cache_tbl_listing.cache_published = 1
		AND (listing_noindex IS NULL OR listing_noindex != 1)	
	    AND tbl_listing.listing_url != '404' AND tbl_listing.listing_url != 'search' AND additional_deleted IS NULL AND
		(listing_name LIKE :search OR
	  listing_content1 LIKE :search OR
	  listing_content2 LIKE :search OR
	  listing_content3 LIKE :search OR
	  listing_content4 LIKE :search OR
	  listing_content5 LIKE :search OR
	  listing_content6 LIKE :search OR
    listing_seo_title LIKE :search OR
    listing_meta_description LIKE :search OR
    listing_meta_words LIKE :search OR
    additional_description LIKE :search OR 
	additional_content1 LIKE :search)
	    ORDER BY type_order ASC";
	$params = array(":search"=>"%".$search."%");

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

function getTags($table, $id){
	global  $DBobject;
	$sql = "SELECT tag_value FROM tbl_tag WHERE tag_object_table = :table AND tag_object_id = :id AND tag_deleted IS NULL";
	return $DBobject->wrappedSql($sql, array(":table"=>$table, ":id"=>$id));
}

function SearchProduct($search){
  global  $CONFIG,$SMARTY,$DBobject;
  $data = array();

  $search = preg_replace("/[^\w+\-\s]/", " ", trim($search, '-'));
  
  //Check if it is variant UID
  
  $sql= "SELECT tbl_variant.*, product_url AS 'cache_url', product_name
		FROM tbl_variant LEFT JOIN tbl_product ON product_id = variant_product_id
		WHERE product_deleted IS NULL AND product_published = 1 AND variant_deleted IS NULL AND 
       product_type_id != 2 AND product_type_id != 3 AND
		variant_uid LIKE :search
        ORDER BY variant_uid DESC";
  $params = array(":search"=>$search."%");
  if ($res = $DBobject->wrappedSql($sql,$params) ) {
    foreach ($res as $r){
      $data ["{$r['cache_url']}"] = $r;
      $data ["{$r['cache_url']}"]['tags'] = getTags('tbl_product',$r['variant_product_id']);
      $sql = "SELECT * FROM tbl_gallery WHERE gallery_variant_id = :id AND gallery_deleted IS NULL";
      $gallery = $DBobject->wrappedSql($sql, array(":id"=>$r['variant_id']));
      if(!empty($gallery)){
        $data ["{$r['cache_url']}"]['gallery'] = $gallery;
      }else{
        $sql = "SELECT * FROM tbl_gallery WHERE gallery_product_id = :id AND gallery_deleted IS NULL";
        $gallery = $DBobject->wrappedSql($sql, array(":id"=>$r['variant_product_id']));
        if(!empty($gallery)){
          $data ["{$r['cache_url']}"]['gallery'] = $gallery;
        }
      }
    }
  }
  
  if(count($data) > 0){
    return $data;
  }

  //IF TAG RESULTS = 0

  $sql= "SELECT tbl_product.*, product_url AS 'cache_url',
		MATCH(product_name,
		product_description,
		product_seo_title,
		product_meta_description,
		product_meta_words) AGAINST (:search) AS Relevance
		FROM tbl_product
		WHERE product_deleted IS NULL AND product_published = 1 AND product_type_id != 2 AND product_type_id != 3 AND
		MATCH(product_name,
		product_description,
		product_seo_title,
		product_meta_description,
		product_meta_words) AGAINST(:search IN
		BOOLEAN MODE) HAVING Relevance > 0.2 
        ORDER BY Relevance DESC";
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

  if(count($data) > 0){
    return $data;
  }

  $sql= "SELECT tbl_product.*, product_url AS 'cache_url'
		FROM tbl_product 
		WHERE product_deleted IS NULL AND product_published = 1 AND product_type_id != 2 AND product_type_id != 3 AND
		(product_name LIKE :search OR
	  product_description LIKE :search OR
    product_seo_title LIKE :search OR
    product_meta_description LIKE :search OR
    product_meta_words LIKE :search )
	    ORDER BY product_order";
  $params = array(":search"=>"%".$search."%");

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

function SearchAdmin($str){
  global $DBobject;

  $data = array();

  $search = preg_replace("/[^\w+\-\s]/", " ", trim($search, '-'));

  $sql = 'SELECT
  tbl_admin.*,
  cache_tbl_listing.cache_url,
  tbl_listing.*,
  MATCH(admin_name, admin_surname) AGAINST(:search) AS Relevance
FROM
  tbl_admin
  LEFT JOIN tbl_access
    ON admin_id = access_admin_id
  LEFT JOIN tbl_listing
    ON access_store_id = listing_object_id
  LEFT JOIN cache_tbl_listing ON listing_object_id = cache_record_id
WHERE access_deleted IS NULL
  AND listing_published = 1
  AND listing_deleted IS NULL
  AND admin_deleted IS NULL
	AND MATCH(admin_name, admin_surname) AGAINST(:search IN BOOLEAN MODE) HAVING Relevance > 0.2 ORDER BY Relevance DESC';
  $params = array(":search"=>"%".$str."%");
  if ($res = $DBobject->wrappedSql($sql,$params) ) {
    foreach ($res as $r){
      $data ["{$r['cache_url']}"] = $r;
    }
  }

  return $data;
}
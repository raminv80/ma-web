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
	$str = clean($_str);
	
	/*$tags['pages']  = SearchPagesTags($str);
	$tags['news']  = SearchNewsTags($str);
	$tags['faq']  = SearchFAQTags($str);
	$tags['video']  = SearchVideoTags($str);
	
	$results['pages']  = array_merge($tags['pages'],SearchPages($str));
	$results['news']  = array_merge($tags['news'],SearchNews($str));
	$results['faq']  = array_merge($tags['faq'],SearchFAQ($str));
	$results['video']  = array_merge($tags['video'],SearchVideo($str));*/
	//$results = SearchListing($str);
	$results = SearchProduct($str);
	$count = count($results);
	
	$SMARTY->assign('count',$count);
	$SMARTY->assign('results',$results);
}

function SearchListing($search){
	global  $CONFIG,$SMARTY,$DBobject;
	$data = array();
	$sql= "SELECT tbl_listing.listing_title,tbl_listing.listing_content1,cache_tbl_listing.cache_url,
		MATCH(tbl_listing.listing_name,
		tbl_listing.listing_title,
		tbl_listing.listing_content1,
		tbl_listing.listing_content2,
		tbl_listing.listing_content3,
		tbl_listing.listing_content4,
		tbl_listing.listing_content5,
		tbl_listing.listing_seo_title,
		tbl_listing.listing_meta_description,
		tbl_listing.listing_meta_words) AGAINST (:search) AS Relevance
		FROM tbl_listing LEFT JOIN cache_tbl_listing ON tbl_listing.listing_id = cache_tbl_listing.cache_record_id 
		WHERE tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_url != '404' AND tbl_listing.listing_url != 'search' AND 
		MATCH(tbl_listing.listing_name,
		tbl_listing.listing_title,
		tbl_listing.listing_content1,
		tbl_listing.listing_content2,
		tbl_listing.listing_content3,
		tbl_listing.listing_content4,
		tbl_listing.listing_content5,
		tbl_listing.listing_seo_title,
		tbl_listing.listing_meta_description,
		tbl_listing.listing_meta_words) AGAINST(:search IN
		BOOLEAN MODE) HAVING Relevance > 0.2 ORDER
		BY Relevance DESC";
	$params = array(":search"=>$search);
	if($res = $DBobject->wrappedSql($sql,$params)){
		foreach($res as $row){
			if(strpos($row['cache_url'],'our-menu') !== false){
				$a = explode('/',$row['cache_url']);
				$url = array_pop($a);
				$row['cache_url'] = "/our-menu#{$url}";
				$data[] = $row;
			}else{
				$data[] = $row;
			}
		}
	}
	return $data;
}


function SearchProduct($search){
	global  $CONFIG,$SMARTY,$DBobject;
	$data = array();
	$sql= "SELECT DISTINCT(cache_url), product_name, product_description, 
			MATCH(product_name,
				product_description,
				product_seo_title,
				product_meta_description,
				product_meta_words,
				product_group
			) AGAINST (:search) AS Relevance1,
			MATCH(attr_value_name ) AGAINST (:search) AS Relevance2
		FROM tbl_product 
			LEFT JOIN cache_tbl_product ON product_id = cache_record_id
			LEFT JOIN tbl_attribute ON product_id = attribute_product_id
			LEFT JOIN tbl_attr_value ON attribute_id = attr_value_attribute_id
		WHERE product_published = 1 AND product_deleted IS NULL AND attribute_deleted IS NULL AND attr_value_deleted IS NULL AND
			( MATCH(product_name,
				product_description,
				product_seo_title,
				product_meta_description,
				product_meta_words,
				product_group
			) AGAINST(:search IN BOOLEAN MODE) OR
			MATCH(attr_value_name ) AGAINST (:search IN BOOLEAN MODE) )
			HAVING Relevance1 > 0.2 OR Relevance2 > 0.2
			ORDER BY Relevance1 DESC, Relevance2 DESC";
	$params = array(":search"=>$search);
	return $DBobject->wrappedSql($sql,$params);
}
/* 
//SEARCH TAGS
function SearchPagesTags($str){
	global $DBobject;	
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
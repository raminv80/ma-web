<?php
global $SMARTY, $DBobject, $CONFIG, $GA_ID;

try{
  $str = $_REQUEST['q'];
  $SMARTY->assign('term',$str);
  $count = 0;
  $tags = array();
  $results = array();
  if(empty($str)){
  	$SMARTY->assign('count',$count);
  	$SMARTY->assign('results',$results);
  	return;
  }
  
  if(!empty($GA_ID)){
    sendGAEvent($GA_ID, 'search', 'input', $str);
  }
  
  /*$tags['pages']  = SearchPagesTags($str);
  $tags['news']  = SearchNewsTags($str);
  $tags['faq']  = SearchFAQTags($str);
  $tags['video']  = SearchVideoTags($str);
  
  $results['pages']  = array_merge($tags['pages'],SearchPages($str));
  $results['news']  = array_merge($tags['news'],SearchNews($str));
  $results['faq']  = array_merge($tags['faq'],SearchFAQ($str));
  $results['video']  = array_merge($tags['video'],SearchVideo($str));*/
  
  $results['pages'] = SearchListing($str);
  $results['products'] = SearchProduct($str);
  
  $SMARTY->assign('count', count($results['pages']) + count($results['products']));
  $SMARTY->assign('results',$results);
  $SMARTY->assign('exists',array());
  
}catch(exceptionCart $e) {
  $SMARTY->assign('error', $e->getMessage());
}

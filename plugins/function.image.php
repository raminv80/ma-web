<?php 
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:	function.banner.php
 * Type:	function
 * Name:	banner
 * Purpose:	outputs formatted data for banner field
 * -------------------------------------------------------------
 */
function smarty_function_image($params, &$smarty)
{
	if(empty($params['field'])){
		return '';
	}
	
	try {
		    $string = strip_tags($params['field'],'<img><a>');
		    $dom = new DOMDocument();
		    $dom->loadHTML($string);
			$body = $dom->getElementsByTagName('body');
			foreach ($body->item(0)->childNodes as $child ){
				if(!empty($child->tagName)){
					if($child->tagName != 'img' ){
						$output = $dom->saveHTML($child);
					}else{
						$output = $dom->saveHTML($child);
					}
					break;
				}
			}
	}catch(Exception $e) {
		return '';
	}
    
    return $output;
}
?>
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
function smarty_function_bannerthumbnails($params, &$smarty)
{
	if(empty($params['field'])){
		return '';
	}
	
	try {
		    $string = strip_tags($params['field'],'<img>');
		    $dom = new DOMDocument();
		    $dom->loadHTML($string);
			$output ='<div id="banner-thumbnails">';
			$body = $dom->getElementsByTagName('body');
			$first = 1;
			foreach ($body->item(0)->childNodes as $child ){
				$output .= '<a href="#" '.($first==1?'class="selected"':'').'  border="0" >'.$dom->saveHTML($child).'</a>';
				$first++;
			}
			$output .='</div>';
	}catch(Exception $e) {
		return '';
	}
    
    return $output;
}
?>

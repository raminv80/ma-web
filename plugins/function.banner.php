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
function smarty_function_banner($params, &$smarty)
{
	if(empty($params['field'])){
		return '';
	}
	
	try {
		    $string = strip_tags($params['field'],'<img><a>');
		    $dom = new DOMDocument();
		    $dom->loadHTML($string);
			$output ='<ul class="slides" >';
			$body = $dom->getElementsByTagName('body');
			foreach ($body->item(0)->childNodes as $child ){
				if(!empty($child->tagName)){
					if($child->tagName != 'img' ){
						//$output .= $child->tagName.' = not img <br/>';
						$output .= '<li data-thumb="'.$child->getElementsByTagName('img')->item(0)->getAttribute('src').'" >'.$dom->saveHTML($child).'</li>';
					}else{
						//$output .= $child->tagName.' = img <br/>';
						$output .= '<li data-thumb="'.$child->getAttribute('src').'" >'.$dom->saveHTML($child).'</li>';
					}
				}
			}
			$output .='</ul>';
	}catch(Exception $e) {
		return '';
	}
    
    return $output;
}
?>
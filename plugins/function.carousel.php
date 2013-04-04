<?php 
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:	function.carousel.php
 * Type:	function
 * Name:	carousel
 * Purpose:	outputs formatted data for banner field
 * -------------------------------------------------------------
 */
function smarty_function_carousel($params, &$smarty)
{
	if(empty($params['field'])){
		trigger_error("assign: missing 'field' parameter");
		return '';
	}
	
	try {
		    $string = strip_tags($params['field'],'<img><a>');
		    $dom = new DOMDocument();
		    $dom->loadHTML($string);
			$output ='<div class="carousel slide" id="myCarousel">
			  				<div class="carousel-inner">';
			$body = $dom->getElementsByTagName('body');
			$class = "active item";
			foreach ($body->item(0)->childNodes as $child ){
				if(!empty($child->tagName)){
					if($child->tagName != 'img' ){
						$output .= '<div class="'.$class.'">'.$dom->saveHTML($child).'</div>';
						$class = "item";
					}else{
						$output .= '<div class="'.$class.'">'.$dom->saveHTML($child).'</div>';
						$class = "item";
					}
				}
			}
			$output .='</div>
						<!-- Carousel nav -->
						<a data-slide="prev" href="#myCarousel" class="carousel-control left"></a>
						<a data-slide="next" href="#myCarousel" class="carousel-control right"></a>
						</div>';
	}catch(Exception $e) {
	    trigger_error("error on banner plugin");
		return '';
	}
    
    return $output;
}
?>
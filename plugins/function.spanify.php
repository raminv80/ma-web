<?php 
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:	function.img_crop.php
 * Type:	function
 * Name:	thumbnail
 * Purpose:	create shink and crop to square version of the image
 * -------------------------------------------------------------
 */

function smarty_function_spanify($params, &$smarty)
{
  $long = 3;
  $short = 1;
  $count = 1;
	if(empty($params['data'])){
		trigger_error("assign: missing 'data' parameter");
		return '';
	}
	if(intval($params['longwords']) > 0){
		$long = intval($params['longwords']);
	}
	if(intval($params['shortwords']) > 0){
	  $short = intval($params['shortwords']);
	}
	try {
		$output.='<span>';
		$string = explode(" ", $params['data']);
		if(count($string)<=$long){$long=$short;}
		$i = 0;
		$n = 0;
		while(true){
		  if($count % 2){
    		while ($n < $long)
    		{
    		  if($n!=0){$output.= " ";}
    			$output.= $string[$i];
    			$i++;
    			$n++;
    			if($i >= count($string)){ break 2; }
    		}
		  }else{
		    while ($n < $short)
		    {
		      if($n!=0){$output.= " ";}
		      $output.= $string[$i];
		      $i++;
		      $n++;
		      if($i >= count($string)){ break 2; }
		    }
		  }

      $n = 0;
		  $count++;
  		$output.='</span><span>';
		}
		$output.='</span>';
		
	}
	catch(Exception $e) {
		return $params['data'];
	}
    
  return $output;
}
?>
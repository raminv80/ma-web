<?php


/**
 *
 * Trim a string to the the number of characters
 * @param string $string
 * @param int $characters
 */
function trim_characters($string,$characters){
	$count= 0 ;
	if(strlen($string) > $characters){
		while($last != ' '){
			$sub = substr($string, 0,$characters-$count);
			$last = substr($sub, -1,1);
			$count++;
		}
	}else{
		$sub=$string;
	}
	return $sub;
}
/**
 *
 * Trim a string to the number of words
 * @param string $str
 * @param int $num_words
 * @return string
 */
function trim_words($str, $num_words){
	$buf='';
	$string = explode(" ", $str);
	$i = 0;
	while ($i < $num_words)
	{
		$buf.= $string[$i]." ";
		$i++;
	}
	if($string[$i]!=null){
		$buf.= "...";
	}
	return 	$buf;
}
/*
 * Write a cookie on user
 * @param string $name  cookie name
 * @param mixed $value cookie value
 * @return bool
 */
function WriteCookie($name,$value,$time=0){
	return setcookie($name,$value, time()+$time);
}

/**
 *
 * Builds the breadcrumbs based on the URI string. Breadcrumbs always contain home.
 */
function processBreadcrumbs(){
	$breadcrumbs = array();
	$breadcrumbs[] = array("link"=>"/", "title"=>"HOME");
	$uri = $_SERVER["REQUEST_URI"];
	$uri = ltrim(rtrim($uri,'/'),'/');
	$array = split('/', $uri);
	foreach($array as $val){
		$link .= '/'.$val;
		$title = strtoupper( trim(str_replace("_", " ", urldecode($val))) );
		$breadcrumbs[] = array("link"=>$link, "title"=>$title);
	}
	return $breadcrumbs;
}

/**
 * Generate random-alphanumeric-character string with a given number for its length.
 * 
 * @param int $length
 * @return string 
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
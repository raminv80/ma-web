<?php

Class QRImage{

	function __construct(){
		
	}
	
	function buildQRvcard($data,$width="",$height="", $error = "H"){
		if(!empty($width)){
			$width = 100;
		}
		if(!empty($height)){
			$height = 100;
		}
		
		$buf = "BEGIN:VCARD VERSION:3.0
		N:{$data['name']}
		FN:{$data['fullname']}
		ORG:{$data['org']}
		TITLE:{$data['title']}
		TEL;TYPE=WORK,VOICE:{$data['phone']}
		TEL;TYPE=MOBILE,VOICE:{$data['mobphone']}
		EMAIL;TYPE=PREF,INTERNET:{$data['email']}
		URL:{$data['url']}
		REV:${($data['revision']?$data['revision']:date("Ymd",time())."T".date("His",time())."Z")}
		END:VCARD";
		return $this->buildQRImage($buf,$width,$height);
	}
	
	function buildQRImage($data,$width="",$height="", $error="H"){
		if(empty($width)){
			$width = 100;
		}
		if(empty($height)){
			$height = 100;
		}
		$url = urlencode($data); 
		
		$qr = file_get_contents("http://{$_SERVER['SERVER_NAME']}/includes/qr-coder/php/qr.php?d={$url}&e={$error}&size={$width}&v=3");
		//$qr = file_get_contents("http://chart.googleapis.com/chart?chs={$width}x{$height}&cht=qr&chld=$error&chl=$url");
		return $qr;
	}
	  
}
?>
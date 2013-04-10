<?php
require 'twitterAPI/EpiCurl.php';
require 'twitterAPI/EpiOAuth.php';
require 'twitterAPI/EpiTwitter.php';

Class Twitter{
	PUBLIC $consumer_key = 'v4woeb7zMwtgHxhM1cVAA';
	PUBLIC $consumer_secret = 'lJEFz9RZHu9zMcJSMhR0tELnXd5AM8rCiSdYXClSHf0';
	PUBLIC $token = '144714241-UDxT1qPHTlNw2fgTpX2UAlo6fW7cdWYiFbKleCuG';
	PUBLIC $secret= 'X3k9o2P12pzov61ajUTTl1ngsz4lNVYxXOYAlMM3Rw';
	function __construct(){
		$this->twitterObj = new EpiTwitter($this->consumer_key, $this->consumer_secret, $this->token, $this->secret);
		$this->twitterObjUnAuth = new EpiTwitter($this->consumer_key, $this->consumer_secret);
		$this->twitterObjUnAuth->getAuthenticateUrl(); 
		//Verify credentials
		$this->creds = $this->twitterObj->get('/account/verify_credentials.json');
	}
	function Search($tag,$count = 50){
		 $this->status = $this->twitterObj->get('/search/tweets.json', array('q' => '#'.$tag , 'count' => $count));
		 return $this->status->response['statuses'];
	}
	
}
/*
$t = new Twitter();
$res = $t->Search('Australia');
print_r($res);
?>


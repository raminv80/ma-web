<?php
require 'facebook/facebook.php';
Class FacebookSearch{
	function __construct($USERID,$TAG =''){
		$this->user = $USERID;
		$this->tag = $TAG;
		$this->loging();
		$this->request = $this->user.'/feed';


	}

	function loging(){
		$this->fb =  new Facebook(array(
				'appId'  => '116033111912597',
				'secret' => '420eadb36f7a00bc815e697bd4052294',
				'cookie' => true
		));
		$user = $this->fb->getUser();
		if ($user) {
			return true;
		}
		return false;
	}

	function Search( $limit = 25 ){

		//$tag_results = $this->TagSearch($limit);
		$wall_results = $this->WallSearch($limit);
        $this->search_results = array();
// 		if(!empty($tag_results)){
// 			$this->search_results=$tag_results;
// 		}
		if(!empty($wall_results)){
			$this->search_results =	array_merge($this->search_results,$wall_results);
		}
		return $this->search_results;
	}

	function TagSearch($limit){
		if ($this->tag == '')$this->tag = $this->user;
		header('Data-tag:'.$this->tag);
		$this->request = "/search?q=%23{$this->tag}&type=post";
		$results = $this->fb->api($this->request);
		return $results['data'];
	}
	function WallSearch($limit){
		if ($this->tag == '')$this->tag = $this->user;
		$this->request = "/{$this->tag}/posts";
		$results = $this->fb->api($this->request);
		return $results['data'];
	}
}
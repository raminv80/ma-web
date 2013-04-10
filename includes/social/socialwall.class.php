<?php
class SocialWall{
	PUBLIC $tag='';
	PUBLIC $limit_youtube = 3;
	PUBLIC $limit_instagram = 3;
	PUBLIC $limit_twitter = 3;
	function __construct($tag){
		$this->tag=$tag;
	}
	function GetSearchResults(){
		$data= array();
		$this->InstagramSearch(&$data,1,$this->limit_instagram);
		$this->TwitterSearch(&$data,$this->limit_twitter);
		$this->YouTubeSearch(&$data, 1 , $this->limit_youtube);
		krsort($data);
		$this->results = $data;//$this->shuffle_assoc($this->results);
		$return = $this->FormatResults($this->results);
		return $return;
	}
	function FormatResults($data){
		$buf='';
		$id=0;
		foreach ($data as $type => $item) {
			
			++$id;
				switch ($item['type']) {
					case 'instagram':
					$buf.=$this->renderinstagramItem($item,$id);
					break;
					case 'twitter':
					$buf.=$this->renderTwitterItem($item,$id);
					break;
					case 'youtube':
					$buf.=$this->renderYouTubeItem($item,$id);
					break;
			
			}
		}
		return $buf;
	}
	function InstagramSearch($data, $min,$max){
		$instagram = new Instagram();
		$instagram_array = $instagram->getTagMedia($this->tag);
		$c = 0;
		foreach ($instagram_array->data as $value) {
			$c++;
			if($c >= $min	&& $c <= $max){
				$value = (array)$value;
				$value['type']='instagram';
				$created = $value['created_time'];
				if(!empty($data[$created])){
					$created = $created+1;
				}
				$data[$created]=$value;
			}
		}
		//return $return;
	}
	function TwitterSearch($data,$count){
		$t = new Twitter();
		$twitter_array = $t->Search($this->tag,$count);
		foreach ($twitter_array as $value) {
			$value = (array)$value;
			$value['type']='twitter';
			$created = strtotime($value['created_at']);
			if(!empty($data[$created])){
				$created = $created+1;
			}
			$data[$created]=$value;
		}
		//return  $return;
	}
	function YouTubeSearch($data,$start = 1 , $limit){
		$y = new youTube;
		$res = $y->search($this->tag,$start , $limit);
		$youtube_array = $y->getResults();
		foreach ($youtube_array as $value) {
			$value = (array)$value;
			$value['type']='youtube';
			$created = strtotime($value['updated']);
			if(!empty($data[$created])){
				$created = $created+1;
			}
			$data[$created]=$value;
		}
		//return $return;
	}
	
	function renderinstagramItem($item,$id){
		$buf.='<div class="instagram" id="item-'.$id.'">
				<a href="'.$item[link].'" target="_blank"><img src="'.$item['images']->low_resolution->url.'" ></a>
				<p>'.$item->caption->text.'<br/><small><img src="'.$item['caption']->from->profile_picture.'" width="60px" >'.$item['caption']->from->username.'</small></p>
			   </div>';
		return  $buf;
	}
	function renderTwitterItem($item,$id){
		$buf.='<div class="twitter" id="item-'.$id.'">
		<h2>'.$item['text'].'</h2>
		<a href="https://twitter.com/'.$item['user']['screen_name'].'/statuses/'.$item['id'].'" target="_blank"><img src="'.$item['user']['profile_image_url'].'" width="60px" ></a>
		</div>';
		return  $buf;	
	}
	function renderYouTubeItem($item,$id){
		$buf.='<div class="youtube" id="item-'.$id.'">
				<h2>'.$item['title'].'</h2>
				<a href="'.$item['player']->default.'" target="_blank"><img src="'.$item['thumbnail']->sqDefault.'" ></a>
				<p>'.$item['description'].'</p>
			   </div>';
		return  $buf;	
	}
	
	//OLD FUNCTION >> RANDOMISES RESULTS
	function shuffle_assoc($list) {
	  if (!is_array($list)) return $list;
	  $keys = array_keys($list);
	  shuffle($keys);
	  $random = array();
	  foreach ($keys as $key)
	    $random[$key] = $list[$key];
	
	  return $random;
	} 

	
}
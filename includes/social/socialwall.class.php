<?php
class SocialWall{
	private $tag='';
	private $table='';
	private $ads=false;
	private $instagram=false;
	private $facebook=false;
	private $youtube=false;
	private $twitter=false;
	private $limit_youtube = 60;
	private $limit_instagram = 60;
	private $limit_twitter = 60;
	private $limit_facebook = 60;
	private $limit_adds = 20;
	
	function __construct($_tag, $_table, $_ads=false,$_instagram=false,$_facebook=false,$_youtube=false,$_twitter=false){
		$this->tag=$_tag;
		$this->table=$_table;
		$this->ads = $_ads;
		$this->instagram = $_instagram;
		$this->facebook = $_facebook;
		$this->youtube = $_youtube;
		$this->twitter = $_twitter;
		$this->UpdateResultDatabase();
	}
	function UpdateResultDatabase(){
		GLOBAL $DBobject;
		$sql = 'SELECT MAX(social_created) AS timed   FROM '.$this->table.' ';
		$time = $DBobject->wrappedSqlGet($sql);
		$lastrequest = strtotime($time[0]['timed']);
		$minutes_after = $lastrequest + (5  *  60) ;
		$seg =  time() -$minutes_after;
		//CHECKS IF TIME GREATER THAN X TO PREVENT HAMMERING THE SOCIAL MEDIA SERVERS (RESULTING IN BANS)
		if( time() > $minutes_after  ){
			$data= array();
			if($this->instagram){ $this->InstagramSearch($data,1,$this->limit_instagram); }
			if($this->facebook){ $this->TwitterSearch($data,$this->limit_twitter); }
			if($this->youtube){ $this->YouTubeSearch($data, 1 , $this->limit_youtube); }
			if($this->twitter){ $this->FacebookSearch($data, $this->limit_facebook); }
			krsort($data);
			$this->storeItemData($data);
		}
		return;
	}
	
	function GetData($_type="",$_limit=""){
		GLOBAL $DBobject;
		$sql = "SELECT * FROM  {$this->table} WHERE social_typeid = :type AND social_deleted IS NULL AND social_tag = :tag ORDER BY social_gmt_timestamp DESC LIMIT {$_limit}";
		$params = array(":tag"=>$this->tag,":type"=>$_type);
		return $DBobject->wrappedSqlGet($sql,$params);
	}
	
	function GetResults( $from = '' ,  $to = '' , $limit = 60 ,$group = 1 , $type = 0){
		header('Data-g:'.$group);
		$buf = $this->ResultsByDate($from = '' ,  $to = '' , $limit  , $group ,$type);
		header('Data-g2:'.$group);
		$return = array('html' =>  str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $buf) , 'datetime' => $this->time , 'groupNumber' => $group);
		$return = json_encode($return, JSON_FORCE_OBJECT);
		return  $return;
	}

	function ResultsByRandom($from = '' ,  $to = '' , $limit = 60 ,$group , $type){
		GLOBAL $DBobject,$SMARTY;

		$data = array();
		$group != 1 ? $group = (60 * $group) - 60  : $group = 0;
		if(empty($from)){
			$this->time = time();
			$data['social_gmt_timestamp']=$this->time;
		}else{
			$data['social_gmt_timestamp']=$from;
		}
		if(!empty($to)){
			$to = ' AND social_gmt_timestamp >= :to';
			$data['to'] = $to;
		}

		if($type != 0 && $type == 1 ){
			$sql = 'SELECT * FROM '.$this->table.' where social_typeid = "1" AND  social_deleted IS NULL  and social_tag = \''.$this->tag.'\'    ORDER BY  social_gmt_timestamp DESC LIMIT  '.$group.' , '.$limit;
			header('debug:'.$sql);
			$instragram  = $DBobject->wrappedSqlGet($sql,$data);
		}
		if($type != 0 && $type == 2 ){
			$sql = 'SELECT * FROM '.$this->table.' where social_typeid = "2" AND  social_deleted IS NULL  and social_tag = \''.$this->tag.'\'    ORDER BY  social_gmt_timestamp DESC LIMIT  '.$group.' , '.$limit;
			$twitter   = $DBobject->wrappedSqlGet($sql,$data);
		}
		if($type != 0 && $type == 3 ){
			$sql = 'SELECT * FROM '.$this->table.' where social_typeid = "3" AND  social_deleted IS NULL  and social_tag = \''.$this->tag.'\'    ORDER BY  social_gmt_timestamp DESC LIMIT  '.$group.' , '.$limit;
			$youtube  = $DBobject->wrappedSqlGet($sql,$data);
		}
		if($type != 0 && $type == 4 ){
			$sql = 'SELECT * FROM '.$this->table.' where social_typeid = "4" AND  social_deleted IS NULL  and social_tag = \''.$this->tag.'\'    ORDER BY  social_gmt_timestamp DESC LIMIT  '.$group.' , '.$limit;
			$facebook  = $DBobject->wrappedSqlGet($sql,$data);
		}

		if($type != 0 && $type == 5 ){
			$sql = 'SELECT * FROM '.$this->table.' where social_typeid = "5" AND  social_deleted IS NULL    ';
			$adds  = $DBobject->wrappedSqlGet($sql,$data);
		}

		if(empty($instragram))$instragram =array();
		if(empty($twitter))$twitter=array();
		if(empty($youtube))$youtube=array();
		if(empty($facebook))$facebook=array();
		$data = array_merge_recursive($instragram,$twitter,$youtube,$facebook);
		if(!empty($data)){
			//add ads
			if($this->ads == true){
				$this->results = array_merge_recursive($instragram,$twitter,$youtube,$facebook,$adds,$adds,$adds);
			}else{
				$this->results = array_merge_recursive($instragram,$twitter,$youtube,$facebook);
			}
			return $this->FormatResults($this->shuffle_assoc($this->results));
		}else{
			return '';
		}
	}

	function ResultsByDate($from = '' ,  $to = '' , $limit = 60 ,$group , $type){
		GLOBAL $DBobject,$SMARTY;
		$data = array();
		$group != 1 ? $group = (60 * $group) - 60 : $group = 0;
		$type != 0 ? $w_type = ' AND social_typeid = "'.intval($type).'" ' : $w_type = '';
		if(empty($from)){
			$this->time = time();
			$data['social_gmt_timestamp']=$this->time;
		}else{
			$data['social_gmt_timestamp']=$from;
		}
		if(!empty($to)){
			$to = ' AND social_gmt_timestamp >= :to';
			$data['to'] = $to;
		}
		$sql = 'SELECT * FROM '.$this->table.' where social_typeid <> "5"  '.$w_type .'  AND social_deleted IS NULL  and social_tag = \''.$this->tag.'\'  ORDER BY  social_gmt_timestamp DESC LIMIT  '.$group.' , '.$limit;
		header('debug:'.$sql);
		$data  = $DBobject->wrappedSqlGet($sql,$data);
		$sql = 'SELECT * FROM '.$this->table.' where social_typeid = "5" AND  social_deleted IS NULL    ';
		$adds  = $DBobject->wrappedSqlGet($sql,$data);
		if(empty($data))$data =array();
		if(!empty($data)){
			//add ads
			//header('debug-ads:'.count($data));
			if($this->ads == true){
				$this->results = array_merge_recursive($data,$adds,$adds);
			}else{
				$this->results = array_merge_recursive($data);
			}

			return $this->FormatResults($this->shuffle_assoc($this->results));
		}else{
			return '';
		}
	}

	function FormatResults($data){
		GLOBAL $SMARTY;
		foreach ($data as $type => $item) {
			$SMARTY->assign('item',$item);
			if(!in_array($item['social_objId'], $_SESSION['control'])){
				if($item['social_typeid'] != 5){
					$_SESSION['control'][]=$item['social_objId'];
				}
				switch ($item['social_typeid']) {
						case '1':
						$buf .= $SMARTY->fetch('instagram-item.tpl');
						break;
						case '2':
						$buf .= $SMARTY->fetch('twitter-item.tpl');
						break;
						case '3':
						$buf .= $SMARTY->fetch('youtube-item.tpl');
						break;
						case '4':
						$buf .= $SMARTY->fetch('facebook-item.tpl');
						break;
						case '5':
						if($buf!=''){
							$buf .= $SMARTY->fetch('ad-item.tpl');
						}

						break;

				}
			}
		}
		return $buf;
	}
	function FormatSingleResult($resultID){
		GLOBAL $SMARTY,$DBobject;
		$sql = 'SELECT * from '.$this->table.' WHERE social_id = :social_id   limit 1 ';
		$item = $DBobject->wrappedSqlGet($sql,array('social_id' => $resultID ));
		$item = $item[0];
		$SMARTY->assign('item',$item);
			switch ($item['social_typeid']) {
				case '1':
					$buf = $SMARTY->fetch('lbox-instagram-item.tpl');
					break;
				case '2':
					$buf = $SMARTY->fetch('lbox-twitter-item.tpl');
					break;
				case '3':
					$buf = $SMARTY->fetch('lbox-youtube-item.tpl');
					break;
		}

		return $buf;

	}
	function InstagramSearch(&$data, $min,$max){
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
	}
	function TwitterSearch(&$data,$count){
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
	}
	function YouTubeSearch(&$data,$start = 1 , $limit){
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
	}
	function FacebookSearch(&$data,$limit){
		$f = new FacebookSearch($this->tag);

		$facebook_array = $f->Search($limit);

		foreach ($facebook_array as $value) {
			$value = (array)$value;
			$value['type']='facebook';
			$created = strtotime($value['created_time']);
			if(!empty($data[$created])){
				$created = $created+1;
			}
			$data[$created]=$value;
		}
	}
	function storeItemData($data){
		GLOBAL $DBobject;
		foreach ($data as $type => $item) {
			switch ($item['type']) {
				case 'instagram':
					$social_typeid=1;
					$social_objId=$item["id"];
					$social_gmt_timestamp = $item["caption"]->created_time;
					$social_profile = $item["user"]->username;
					$social_profile_img =  $item["user"]->profile_picture;
					$social_content = $item["caption"]->text;
					$social_image =$item["images"]->low_resolution->url;
					$social_link = $item["link"];
					break;
				case 'twitter':
					$social_typeid=2;
					$social_objId=$item['id'];
					$social_gmt_timestamp = strtotime($item["created_at"]);
					$social_profile = $item['user']['screen_name'];
					$social_profile_img = $item['user']['profile_image_url'];
					$social_content = $item['text'];
					$social_image ='';
					$social_link ='https://twitter.com/'.$item['user']['screen_name'].'/statuses/'.$item['id'];
					break;
				case 'youtube':
					$social_typeid=3;
					$social_objId=$item["id"];
					$social_gmt_timestamp = strtotime($item["updated"]);
					$social_profile = $item["uploader"];
					$social_profile_img =  '';
					$social_content = $item["title"];
					$social_image =$item["thumbnail"]->hqDefault;
					$social_link = $item["player"]->default;
				break;
				case 'facebook':
					$social_typeid=4;
					$social_objId=$item["id"];
					$social_gmt_timestamp = strtotime($item["created_time"]);
					$social_content = $item["message"];
					$social_image =$item["picture"];
					$social_link = $item["link"];
					$social_profile_img =  '';
				break;

			}

			$sql = 'SELECT social_objId from '.$this->table.' WHERE social_objId = :social_objId AND social_typeid = :social_typeid limit 1 ';
			$res = $DBobject->wrappedSqlGet($sql,array('social_objId' => $social_objId , 'social_typeid' => $social_typeid ));
			if($res == false){
				$data = array(
						'social_typeid' => $social_typeid,
						'social_objId' => $social_objId,
						'social_gmt_timestamp' => $social_gmt_timestamp,
						'social_profile' => $social_profile ,
						'social_profile_img' => $social_profile_img ,
						'social_content' => $social_content ,
						'social_image' => $social_image ,
						'social_link'  => $social_link
				);
				$sql = 'INSERT INTO '.$this->table.'
						(
						social_typeid,
						social_objId,
						social_gmt_timestamp,
						social_profile,
						social_profile_img,
						social_content,
						social_image,
						social_link,
						social_tag
						)
						VALUES
						(
						:social_typeid,
						:social_objId,
						:social_gmt_timestamp,
						:social_profile,
						:social_profile_img,
						:social_content,
						:social_image,
						:social_link,
						"'.$this->tag.'"
						)
						';
				if(!empty($social_objId)){
				$res = $DBobject->wrappedSqlInsert($sql,$data);
				}
			}else{
				$d++;
			}
		}
	}


	// RANDOMISES RESULTS
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
<?php
global $CONFIG,$DBobject;

$i=1;
while(true){
	$f = loadFeed('cpynesturt',$i,50);
	if(empty($f) || count($f) <= 0){ break; }
	importFeed($f);
	$i = $i+50;
}

echo "complete ";

function importFeed($feed){
	global $CONFIG,$DBobject;
	foreach($feed as $row){
		
		$id = end(explode('/',$row['id']['$t']));
		$created = date("Y-m-d",strtotime($row['published']['$t']));
		$title = $row['media$group']['media$title']['$t'];
		$description = $row['media$group']['media$description']['$t'];
		$image = $row['media$group']['media$thumbnail'][0]['url'];
		
		$save_folder = $_SERVER['DOCUMENT_ROOT'].'uploads/youtube/';
		$save_image = $save_folder."{$id}.jpg";
		$image_url = '/uploads/youtube/'."{$id}.jpg";
		//grab_image($image,$save_image);
		
		$params = array();
		$params['listing_object_id'] = "";
		$params['listing_type_id'] = 4; //Fixed based on table being imported
		$params['listing_parent_id'] = 120; //Fixed based on table being imported
	
		$params['listing_name'] = $title; //post_title
		$params['listing_url'] = urlSafeString($title); //post_name
		$params['listing_seo_title'] = $title; //post_title
		$params['listing_image'] = $image_url; //youtube image
		$params['listing_content1'] = $id; //youtube ID
		$params['listing_meta_description'] = excerpt($description); //excerpt ( post_content )
		$params['listing_published'] = 1; //1
		$params['listing_created'] = $created; //now()
		$params['listing_schedule_start_date'] = $created; //post_date
		$effectiveDate = strtotime("+12 months", strtotime($created));
		$effectiveDate = strftime ( '%Y-%m-%d' , $effectiveDate );
		$params['listing_schedule_end_date'] = $effectiveDate; //post_date + 3 months
		$params['listing_share_title'] = $row['post_title']; //post_title
		$params['listing_share_image'] = $image; //post_title
		$params['listing_share_text'] = excerpt($description); //Read post_title
	
		$checksql = "SELECT * FROM tbl_listing WHERE listing_url = :url AND listing_image IS NULL";
		$chk = $DBobject->executeSQL($checksql,array('url'=>$params['listing_url']));
		if(empty($chk[0])){
			/* $sql = "INSERT INTO tbl_sequence () VALUES ()";
			$DBobject->wrappedSql($sql);
			$objID = $DBobject->wrappedSqlIdentity();
			$params['listing_object_id'] = $objID;
	
			$sqlfields = array();$sqlvalues = array();
			foreach($params as $key => $p){
				$sqlfields[] = "{$key}";
				$sqlvalues[] = ":{$key}";
			}
			$sqlinsert = "INSERT INTO tbl_listing (".implode(",",$sqlfields).") VALUES (".implode(",",$sqlvalues).")";
			$DBobject->wrappedSql($sqlinsert,$params); */
		}else{
			/*$sqlup = "UPDATE tbl_listing SET listing_image = '{$image_url}' WHERE listing_id = :id";
			$DBobject->wrappedSql($sqlup,array("id"=>$chk[0]['listing_id']));*/
			/* $sql = "INSERT INTO tbl_sequence () VALUES ()";
			$DBobject->wrappedSql($sql);
			$objID = $DBobject->wrappedSqlIdentity();
			$params['listing_object_id'] = $objID;
			
			$sqlfields = array();$sqlvalues = array();
			foreach($params as $key => $p){
				$sqlfields[] = "{$key}";
				$sqlvalues[] = ":{$key}";
			}
			$sqlfields[] = "listing_deleted";
			$sqlvalues[] = ":listing_deleted";
			$sqlinsert = "INSERT INTO tbl_listing_copy (".implode(",",$sqlfields).") VALUES (".implode(",",$sqlvalues).")";
			$DBobject->wrappedSql($sqlinsert,array_merge($params,array('listing_deleted'=>date("Y-m-d")))); */
			/* $sqlup = "UPDATE tbl_listing SET listing_deleted = null WHERE listing_id = :id";
			$DBobject->wrappedSql($sqlup,array("id"=>$chk[0]['listing_id'])); */
		} 
	}
}

function excerpt($str){
	$output='';
	$string = explode(" ", $str);
	$i = 0;
	while ($i < 25)
	{
		$output.= $string[$i]." ";
		$i++;
	}
	if($string[$i]!=null){
		$output.= "...";
	}
	return $output;
}

function loadFeed($user,$index=1, $max=50){

	$details_url = "http://gdata.youtube.com/feeds/users/{$user}/uploads?max-results={$max}&start-index={$index}&alt=json";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $details_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$feed = json_decode(curl_exec($ch), true);

	return $feed['feed']['entry'];
}

function grab_image($url,$saveto){
	$ch = curl_init ($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
	$raw=curl_exec($ch);
	curl_close ($ch);
	if(file_exists($saveto)){
		unlink($saveto);
	}
	$fp = fopen($saveto,'x');
	fwrite($fp, $raw);
	fclose($fp);
}


<?php
class ProductListing {
	function __construct($_URL, $_CONFIG_OBJ){
		global $SMARTY,$DBobject;
		$this->URL = $_URL;
		$this->CONFIG_OBJ = $_CONFIG_OBJ;
		$this->init($this->URL);

	}

	protected function init($_URL){
		global $DBobject;
		$params = array();
		$params = explode('/', $_URL);
		$product =  FALSE;
		if(count($params) >= 1){
			foreach ($params as $value){
				if($value != ''){
					//is a product
					$p_name = str_replace('-','%',$value);
					$sql = 'SELECT * from tbl_listing where listing_name LIKE :p_name AND listing_type_id = "'.$this->CONFIG_OBJ->type.'" ';
					$product_data = $DBobject->wrappedSqlGet($sql,array('p_name'=>'%'.$p_name.'%'));
					if(!empty($product_data)){
						$this->template = '';
						$url_where = ' AND listing.listing_id = "'.$product_data[0]['listing_id'].'" ';
						$this->template = $this->CONFIG_OBJ->item_template;
						$this->product = str_replace('%',' ',$p_name);
						$product = true;
						break;
					}
					//is a category
					$cat_name = str_replace('-','%',$value);
					$sql = 'SELECT * from tbl_category where category_name  LIKE :category_name AND category_type_id = "'.$this->CONFIG_OBJ->type.'" ';
					$category_data = $DBobject->wrappedSqlGet($sql,array('category_name'=>'%'.$cat_name.'%'));
					if(!empty($category_data)){
						$this->template = '';
						$url_where = ' AND pc.category_id = "'.$category_data[0]['category_id'].'" ';
						$this->template = $this->CONFIG_OBJ->category_template;
						$this->category = str_replace('%',' ',$cat_name);
						$this->category_data =unclean($category_data[0]);

					}

				}
			}


			$sql='
					SELECT
					  listing.*,
					  pc.*,
					  cc.*
					FROM tbl_category AS pc
					LEFT JOIN
					tbl_category AS cc ON
					cc.category_parent_id = pc.category_id
					LEFT JOIN
					tbl_link AS link ON
					link.link_category_id = cc.category_id
					LEFT JOIN
					tbl_listing AS listing ON
					listing.listing_id = link.link_list_id

					WHERE
					listing.listing_deleted IS NULL
					AND pc.category_deleted IS NULL
					AND cc.category_deleted IS NULL
					AND link.link_deleted IS NULL
					AND pc.category_type_id = "'.$this->CONFIG_OBJ->type.'"
					AND pc.category_parent_id = "0"
					'.$url_where.'

					';
			//die($sql);
				$this->raw_data['products'] = $DBobject->wrappedSql($sql);
				$sql='
					SELECT
						pc.*,
						cc.*
					FROM tbl_category AS pc
					  LEFT JOIN tbl_category AS cc
					    ON cc.category_parent_id = pc.category_id
					WHERE pc.category_deleted IS NULL
					    AND cc.category_deleted IS NULL
					    AND pc.category_type_id = "'.$this->CONFIG_OBJ->type.'"
					    AND pc.category_parent_id = "0"
					'.$url_where.'

					';
				$this->raw_data['categories'] = $DBobject->wrappedSql($sql);
		}

		//get all product galleries
		$arr =array();
		$gal_ret = array();
		$sql='
		SELECT *
		FROM tbl_gallery as g
		WHERE g.gallery_deleted IS NULL
		   AND g.gallery_listing_id IS NOT NULL
		  AND g.gallery_listing_id <>  "0"
		';
		$res = $DBobject->wrappedSql($sql);
		foreach ($res as $arr){
			$gal_ret[$arr['gallery_listing_id']][] = $arr;
		}
	//	die(print_r($gal_ret));
		$this->raw_data['galleries'] = unclean($gal_ret);
			if(empty($this->raw_data)){
					header("Location: /404");
					die();
				}

	}

	function Load(){
		global $SMARTY;
		$data = array();
		foreach ($this->raw_data as $type =>  $array) {
			foreach ($array as $index =>  $row) {
				foreach ($row as $key => $field) {
					if(!is_numeric($key)){
						if( !in_array($field,$data[$type]["{$row[key($row)]}"]["{$key}"]) ){// && $data[$type]["{$row[key($row)]}"]["{$key}"] != unclean($field) ){
							//if(empty($data[$type]["{$row[key($row)]}"]["{$key}"])){
								$data[$type]["{$row[key($row)]}"]["{$key}"] = unclean($field);
// 							}else{
// 								if(!is_array($data[$type]["{$key}"])){
// 									$temp = $data[$type]["{$key}"];
// 									$data[$type]["{$row[key($row)]}"]["{$key}"]=array($temp);
// 								}
// 								$data[$type]["{$row[key($row)]}"]["{$key}"][] = unclean($field);
// 							}

						}
					}
				}
			}
		}
		$data['galleries']=$this->raw_data['galleries'];

		$SMARTY->assign('product_name',$this->product);
		$SMARTY->assign('category_name',$this->category);
		$SMARTY->assign('data',$data);
		$SMARTY->assign('category_data',$this->category_data);
		return $this->template;
	}

}
<?php
class ProductClass extends ListClass {
  private $DBPRODTABLE;
  private $TYPE_ID;
  private $ROOT_PARENT_ID;
  private $IS_PRODUCT;
  private $DBPRODTABLE_URL;

  function __construct($_URL, $_CONFIG_OBJ) {
    parent::__construct($_URL,$_CONFIG_OBJ);
    $this->TYPE_ID = $_CONFIG_OBJ->type;
    $this->ROOT_PARENT_ID = $_CONFIG_OBJ->root_parent_id;
    $this->DBTABLE = '';
    $this->DBPRODTABLE = $_CONFIG_OBJ->table->name; //tbl_product
    $this->DBPRODTABLE_KEY = $_CONFIG_OBJ->table->id; //product_id
    $this->DBPRODTABLE_OBJ_KEY = empty($_CONFIG_OBJ->table->objid) ? 'product_object_id' : (string)$_CONFIG_OBJ->table->objid;
    $this->DBPRODTABLE_URL = $_CONFIG_OBJ->table->field; //product_url
  }

  function Load($_ID = null, $_PUBLISHED = 1) {
    global $SMARTY,$DBobject;
    $template = "";

    // Split the URL
    $_url = ltrim(rtrim($this->URL,'/'),'/');

    $LOCALCONF = $this->CONFIG_OBJ->table;
    
    $pre = str_replace("tbl_", "", $this->DBPRODTABLE);
    $extends = "";
    foreach($LOCALCONF->extends as $a){
      $extends .= " LEFT JOIN {$a->table} ON {$a->linkfield} = {$a->field}";
    }
    $sql = "SELECT * FROM {$this->DBPRODTABLE} {$extends} 
    WHERE {$this->DBPRODTABLE_URL} = :url 
    AND ({$pre}_deleted IS NULL OR {$pre}_deleted = '0000-00-00') AND {$pre}_published = :published LIMIT 1";
    $params = array(
        ":url" => $_url,
        ":published" => $_PUBLISHED
    );
    if($res = $DBobject->wrappedSqlGet($sql,$params)){
      $this->ID = $res[0]["{$this->DBPRODTABLE_OBJ_KEY}"]; 
      foreach($res[0] as $key=>$val){
        $SMARTY->assign($key,unclean($val));
      }
      
      // ------------- LOAD PRODUCT GENERAL DETAILS --------------
      if(!empty($this->ID)){
        $general_details = $this->GetProductGeneralDetails($this->ID);
        $SMARTY->assign('general_details', unclean($general_details));
      }
      
      // ------------- LOAD ASSOCIATED TABLES --------------
      foreach($LOCALCONF->associated as $a){
        $t_data = $this->LoadAssociated($a,$res[0]["{$a->linkfield}"]);
        $SMARTY->assign("{$a->name}", unclean($t_data));
      }
      
      //Set cookie
      $this->SetCookieRecentView();
      
    }else{
      return null;
    }
    // ------------- LOAD OPTIONS FOR SELECT INPUTS --------------
    foreach($LOCALCONF->options->field as $f){
      if($f->attributes()->recursive){
        $options = $this->getOptionsCatTree($f, $f->attributes()->parent_root);
      }else{
        $pre = str_replace("tbl_","",$f->table);
        $extraArr = array();
        foreach($f->extra as $xt){
        	$extraArr[] = (string) $xt;
        }
        $extraStr = !empty($extraArr)?",".implode(',', $extraArr):"";
        $sql = "SELECT {$f->id},{$f->reference} {$extraStr} FROM {$f->table} 
        WHERE ({$pre}_deleted IS NULL OR {$pre}_deleted = '0000-00-00') " . ($f->where != ''?"AND {$f->where} ":"") . " 
        " . ($f->orderby != ''?" ORDER BY {$f->orderby} ":"");
        if($res = $DBobject->wrappedSqlGet($sql)){
          $options = array();
          foreach($res as $row){
          	$options[$row["{$f->id}"]]['id'] = $row["{$f->id}"];
          	$options[$row["{$f->id}"]]['value'] = $row["{$f->reference}"];
          	foreach($extraArr as $xt){
          		$options[$row["{$f->id}"]]["{$xt}"] = $row["{$xt}"];
          	}
          }
        }
      }
      $SMARTY->assign("{$f->name}",unclean($options));
    }
    $template = $this->CONFIG_OBJ->template;
    return $template;
  }

  

  function LoadAssociatedByTag($cfg) {
    global $CONFIG,$SMARTY,$DBobject;
    
    if(! $this->IS_PRODUCT){
      parent::LoadAssociatedByTag($cfg);
    }else{
      foreach($cfg->producttable->tags as $a){
        $preObjTbl = str_replace("tbl_","",$a->object_table);
        $sql = "SELECT  {$a->object_value} AS VALUE  FROM {$a->object_table} WHERE {$preObjTbl}_id = :id 
        AND ({$preObjTbl}_deleted IS NULL OR {$preObjTbl}_deleted = '0000-00-00') AND {$preObjTbl}_published = 1 ";
        $params = array(
            ":id"=>$this->ID
        );
        if($res = $DBobject->wrappedSqlGet($sql,$params)){
          
          $group = "";
          $order = "";
          $limit = "";
          $where = "";
          if(! empty($a->groupby)){
            $group = " GROUP BY " . $a->groupby;
          }
          if(! empty($a->orderby)){
            $order = " ORDER BY " . $a->orderby;
          }
          if(! empty($a->limit)){
            $limit = " LIMIT " . $a->limit;
          }
          if(! empty($a->where)){
            $where = " AND " . $a->where;
          }
          
          $pre = str_replace("tbl_","",$a->table);
          $sql = "SELECT {$a->object_table}.* FROM {$a->table} LEFT JOIN {$a->object_table} ON  {$preObjTbl}_id = {$pre}_object_id
					WHERE {$pre}_object_table = :objTable AND {$pre}_value = :objValue  
					AND ({$pre}_deleted IS NULL OR {$pre}_deleted = '0000-00-00')
					AND ({$preObjTbl}_deleted IS NULL OR {$preObjTbl}_deleted = '0000-00-00') 
					AND {$preObjTbl}_published = 1 " . $where . " " . $group . " " . $order . " " . $limit;
          $params = array(
              ':objTable'=>$a->object_table,
              ':objValue'=>$res[0]["VALUE"]
          );
          
          $data = array();
          if($objs = $DBobject->wrappedSqlGet($sql,$params)){
            foreach($objs as $o){
              $data["{$o["{$preObjTbl}_id"]}"] = $o;
              $url = $o["{$preObjTbl}_url"] . '-' . $o["{$preObjTbl}_id"];
              $this->BuildUrl($o["{$preObjTbl}_listing_id"],$url);
              $data["{$o["{$preObjTbl}_id"]}"]["absolute_url"] = $url;
              
              foreach($cfg->producttable->associated as $b){
                $data["{$o["{$preObjTbl}_id"]}"]["{$b->name}"] = $this->LoadAssociated($b,$o["{$b->linkfield}"]);
              }
            }
          }
          $SMARTY->assign("{$a->name}",$data);
        }
      }
    }
  }
 
  
  /**
   * Return product base general details - all attributes and price range
   *
   * @param int $cartId
   * @return float
   */
  function GetProductGeneralDetails($productObjId) {
    global $DBobject;
  
    $result = array();
  
    //Set default values
    $result['id'] = $productObjId;
    $result['price']['min'] = 999999;
    $result['price']['max'] = 0;
    $result['instock']['flag'] = 0;
    $result['instock']['variants'] = array();
    $result['limitedstock']['flag'] = 0;
    $result['limitedstock']['variants'] = array();
    $result['new']['flag'] = 0;
    $result['new']['variants'] = array();
    $result['has_attributes'] = array();
    $result['has_parent_attributes'] = array();
    $result['image'] = '';
    $variantImgArr = array(); 
    
    $params = array(":id" => $productObjId);
    
    //Get hero image
    // Removed - AND (gallery_variant_id IS NULL OR gallery_variant_id = 0) to display image
    $sql = "SELECT gallery_link, gallery_variant_id 
    FROM tbl_gallery LEFT JOIN tbl_product ON product_id = gallery_product_id
    WHERE (gallery_deleted IS NULL OR gallery_deleted = '0000-00-00') 
    AND gallery_link IS NOT NULL AND gallery_link != '' 
    AND (product_deleted IS NULL OR product_deleted = '0000-00-00') 
    AND product_published = 1 AND product_object_id = :id 
    ORDER BY gallery_variant_id, gallery_order";
    if($res2 = $DBobject->wrappedSql($sql, $params)){
      $result['image'] = $res2[0]['gallery_link'];
      foreach($res2 as $r){
        if(empty($variantImgArr[$r['gallery_variant_id']])){
          $variantImgArr[$r['gallery_variant_id']] = $r['gallery_link'];
        }
      }
    }
  
    //Check all variants
    $sql = "SELECT tbl_variant.*, product_id FROM tbl_product LEFT JOIN tbl_variant ON variant_product_id = product_id
        WHERE (product_deleted IS NULL OR product_deleted = '0000-00-00') 
        AND product_published = 1 AND product_object_id = :id 
        AND (variant_deleted IS NULL OR variant_deleted = '0000-00-00') AND variant_published = 1";
    if($res = $DBobject->wrappedSql($sql, $params)){
      foreach($res as $r){
        $price = $r['variant_price'];
        if($r['variant_specialprice'] > 0){
          $price = $r['variant_specialprice'];
          $result['sale']['flag'] = 1;
          $result['sale']['variants'][] = $r['variant_id'];
        }
        if($price < $result['price']['min']){
          $result['price']['min'] = $price;
        }
        if($price > $result['price']['max']){
          $result['price']['max'] = $price;
        }
        if(!empty($r['variant_instock'])){
          $result['instock']['flag'] = 1;
          $result['instock']['variants'][] = $r['variant_id'];
        }
        if(!empty($r['variant_limitedstock'])){
          $result['limitedstock']['flag'] = 1;
          $result['limitedstock']['variants'][] = $r['variant_id'];
        }
        if(!empty($r['variant_new'])){
          $result['new']['flag'] = 1;
          $result['new']['variants'][] = $r['variant_id'];
        }
  
        //Create materials array
        $sql = "SELECT pmaterial_name, pmaterial_id FROM tbl_pmateriallink LEFT JOIN tbl_pmaterial ON pmaterial_id = pmateriallink_record_id 
            WHERE (pmateriallink_deleted IS NULL OR pmateriallink_deleted = '0000-00-00') 
            AND (pmaterial_deleted IS NULL OR pmaterial_deleted = '0000-00-00') 
            AND pmateriallink_product_id = :id GROUP BY pmaterial_id";
        if($attr = $DBobject->wrappedSql($sql, array(":id" => $r['product_id']))){
          foreach($attr as $a){
            $result['materials'][$a['pmaterial_id']] = $a['pmaterial_name'];
          }
        }
        
        //Create usages array
        $sql = "SELECT pusage_name, pusage_id FROM tbl_pusagelink LEFT JOIN tbl_pusage ON pusage_id = pusagelink_record_id
            WHERE (pusagelink_deleted IS NULL OR pusagelink_deleted = '0000-00-00') 
            AND (pusage_deleted IS NULL OR pusage_deleted = '0000-00-00') 
            AND pusagelink_product_id = :id GROUP BY pusage_id";
        if($attr = $DBobject->wrappedSql($sql, array(":id" => $r['product_id']))){
          foreach($attr as $a){
            $result['usages'][$a['pusage_id']] = $a['pusage_name'];
          }
        }
        
        //Create attributes array
        $sql = "SELECT tbl_productattr.*, attribute_name, attribute_type, attr_value_name, attr_value_image, attr_value_associates FROM tbl_productattr
            LEFT JOIN tbl_attribute ON attribute_id = productattr_attribute_id
            LEFT JOIN tbl_attr_value ON attr_value_id = productattr_attr_value_id
            WHERE (productattr_deleted IS NULL OR productattr_deleted = '0000-00-00') 
            AND attr_value_id IS NOT NULL AND productattr_variant_id = :id GROUP BY attr_value_id";
        if($attr = $DBobject->wrappedSql($sql, array(":id" => $r['variant_id']))){
          foreach($attr as $a){
            $attrArr = array(
                'attribute_name' => $a['attribute_name'], 
                'attribute_name' => $a['attribute_name'], 
                'attr_value_name' => $a['attr_value_name'], 
                'attr_value_image' => $a['attr_value_image'] 
            );
            $result['has_attributes'][$a['productattr_attribute_id']][$a['productattr_attr_value_id']]['values'] = $attrArr;
            if(!empty($variantImgArr[$r['variant_id']]) && empty($result['has_attributes'][$a['productattr_attribute_id']][$a['productattr_attr_value_id']]['product_image'])){
              $result['has_attributes'][$a['productattr_attribute_id']][$a['productattr_attr_value_id']]['product_image'] = $variantImgArr[$r['variant_id']];
            }
            $result['has_attributes'][$a['productattr_attribute_id']][$a['productattr_attr_value_id']]['variants'][] = $r['variant_id'];
            if(!empty($a['attr_value_associates']) && isJson($a['attr_value_associates'])){
              //Set PARENT attr-values array
              $result['has_parent_attributes'] = array_unique(array_merge($result['has_parent_attributes'], json_decode($a['attr_value_associates'])), SORT_REGULAR);
              $result['has_attributes'][$a['productattr_attribute_id']][$a['productattr_attr_value_id']]['values']['parents'] = $result['has_parent_attributes'];
            }
          }
        }
        
      }
      
    }
    return $result;
  }
  
  
  protected function SetCookieRecentView(){
  
    try{
      $name = 'prdarr';
      $maxValues = 16;
      $valuesArr = array();
      
      //Has product cookie
      if(!empty($_COOKIE[$name])){
        $tempvaluesArr = explode('.', $_COOKIE[$name]);
        
        //Clean values
        foreach($tempvaluesArr as $v){
          $nval = hexdec($v);
          if(is_numeric($nval)){
            $valuesArr[] = dechex($nval); 
          }
        }
      }
      
      //Remove product_object_id if it's in array
      $newValue = dechex($this->ID);
      if(in_array($newValue, $valuesArr)){
        $valuesArr = array_diff($valuesArr, array($newValue));
      }
      
      //Remove existing products  
      while(count($valuesArr) >= $maxValues){
        array_shift($valuesArr);
      }
      
      //Insert product_object_id
      $valuesArr[] = $newValue;
      
      $value =  implode('.', $valuesArr);
      
      // SET COOKIE
      $_SECURE_COOKIE = false;
      if($_SERVER['SERVER_PORT'] == 443 || !empty($_SERVER['HTTPS'])){
        $_SECURE_COOKIE = true; /* IF HTTPs TURN THIS ON */
      }
      
      $currentCookieParams = session_get_cookie_params();
      
      setcookie($name, // name
          $value, // value
          (time() + (60 * 60 * 24 * 365)), //1 year - expires at end of session
          $currentCookieParams['path'], // path
          $currentCookieParams['domain'], // domain
          $_SECURE_COOKIE, // secure
          true) // httponly: Only accessible via http. Not accessible to javascript
          ;
      
    } catch (Exception $e) {}
    return false;
  }
  
  
  
  function GetRecentViewProduct(){
    global $DBobject;
    
    $name = 'prdarr';
    $results = array();
    if(!empty($_COOKIE[$name])){
      $tempvaluesArr = explode('.', $_COOKIE[$name]);
      
      foreach($tempvaluesArr as $v){
        $nval = hexdec($v);
        if(is_numeric($nval)){
          $sql = "SELECT product_object_id, product_name, product_url, product_brand, product_meta_description, product_associate1 
            FROM tbl_product
            WHERE (product_deleted IS NULL OR product_deleted = '0000-00-00') 
            AND product_published = 1 AND product_object_id = :id";
          if($products = $DBobject->wrappedSql($sql, array(':id' => $nval))){
            foreach($products as &$p){
              $p['general_details'] = $this->GetProductGeneralDetails($p['product_object_id']);
              $results[] = $p;
            }
          }
        }
      }
    }
    return array_reverse($results);
  }
}

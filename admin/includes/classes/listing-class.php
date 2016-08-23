<?php
class Listing {
  protected $CONFIG_OBJ;
  protected $DBTABLE;
  // URL value passed into constructor
  protected $TYPE_ID;
  // SQL ELEMENTS
  protected $SELECT = "*";
  protected $TABLES = "";
  protected $WHERE = "";
  protected $ORDERBY = "";
  protected $GROUPBY = "";
  protected $LIMIT = "";
  // SET OF DATA LOADED
  protected $DATA;

  function __construct($_sp) {
    global $SMARTY,$DBobject;
    $this->CONFIG_OBJ = $_sp;
    $this->TYPE_ID = $_sp->type_id;
    $this->WHERE = $_sp->where;
    $this->DBTABLE = " tbl_listing
							LEFT JOIN tbl_type ON tbl_listing.listing_type_id = tbl_type.type_id";
  }

  function getListing($id) {
    global $SMARTY,$DBobject;
    $listing_f = array();
    // $sql = "SELECT * FROM {$this->DBTABLE} WHERE listing_id = '{$id}' AND listing_deleted IS NULL " . ($this->WHERE != '' ? "AND {$this->WHERE} " : " ") . " ";
    $sql = "SELECT * FROM {$this->DBTABLE} WHERE listing_id = '{$id}' " . ($this->WHERE != ''?"AND {$this->WHERE} ":" ") . " ";
    if($res = $DBobject->wrappedSqlGet($sql)){
      foreach($res as $row){
        foreach($row as $key=>$field){
          if(! is_numeric($key)){
            if(! in_array($field,$listing_f["{$key}"]) && $listing_f["{$key}"] != $field){
              if(empty($listing_f["{$key}"])){
                $listing_f["{$key}"] = $field;
              }else{
                if(! is_array($listing_f["{$key}"])){
                  $temp = $listing_f["{$key}"];
                  $listing_f["{$key}"] = array(
                      $temp
                  );
                }
                $listing_f["{$key}"][] = $field;
              }
            }
          }
        }
      }
      
      foreach($this->CONFIG_OBJ->associated as $a){
        $listing_f["{$a->name}"] = $this->getAssociated($a,$res[0]["{$a->linkfield}"]);
      }
      foreach($this->CONFIG_OBJ->log as $a){
      	$listing_f["logs"] = $this->getLog($a,$res[0]["{$a->id}"]);
      }
      foreach($this->CONFIG_OBJ->extends as $a){
        $pre = str_replace("tbl_","",$a->table);
        $sql = "SELECT * FROM {$a->table} WHERE {$a->field} = '".$res[0]["{$a->linkfield}"]."' AND {$pre}_deleted IS NULL ";
        if($res2 = $DBobject->wrappedSqlGet($sql)){
          foreach($res2[0] as $key=>$field){
            if(empty($listing_f["{$key}"])){
              $listing_f["{$key}"] = $field;
            }else{
              if(! is_array($listing_f["{$key}"])){
                $temp = $listing_f["{$key}"];
                $listing_f["{$key}"] = array(
                    $temp
                );
              }
              $listing_f["{$key}"][] = $field;
            }
          }
        }
      }
    }
    foreach($this->CONFIG_OBJ->options->field as $f){
      if($f->attributes()->recursive){
        $parentID = 0;
        if($this->CONFIG_OBJ->root_parent_id){
          $parentID = $this->CONFIG_OBJ->root_parent_id;
        }
        if(!empty($f->parent_id)){
          $parentID = $f->parent_id;
        }
        $listing_f['options']["{$f->name}"] = $this->getOptionsCatTree($f,$parentID);
      }else{
        $pre = str_replace("tbl_","",$f->table);
        $sql = "SELECT {$f->id},{$f->reference} FROM {$f->table} WHERE {$pre}_deleted IS NULL " . ($f->where != ''?"AND {$f->where} ":"") . ($f->groupby != '' ? " GROUP BY {$f->groupby} " : "") . ($f->orderby != ''?" ORDER BY {$f->orderby} ":"");
        if($res = $DBobject->wrappedSqlGet($sql)){
          foreach($res as $key=>$row){
            $listing_f['options']["{$f->name}"][] = array(
                'id'=>$row["{$f->id}"],
                'value'=>$row["{$f->reference}"]
            );
          }
        }
      }
    }
    return $listing_f;
  }

  function getOptionsCatTree($f, $pid) {
    global $SMARTY,$DBobject;
    $results = array();
    
    $pre = str_replace("tbl_","",$f->table);
    $sql = "SELECT {$f->id}, {$f->reference} FROM {$f->table} WHERE {$pre}_deleted IS NULL AND {$pre}_parent_id = {$pid} " . ($f->where != ''?"AND {$f->where} ":"") . ($f->groupby != '' ? " GROUP BY {$f->groupby} " : "") . ($f->orderby != ''?" ORDER BY {$f->orderby} ":"");
    
    if($res = $DBobject->wrappedSqlGet($sql)){
      foreach($res as $row){
        $results[] = array(
            'id'=>$row["{$f->id}"],
            'value'=>$row["{$f->reference}"],
            'subs'=>$this->getOptionsCatTree($f,$row["{$f->id}"])
        );
      }
    }
    return $results;
  }

  function getAssociated($a, $id) {
    global $SMARTY,$DBobject;
    $results = array();
    
    $extends = "";
    foreach($a->extends as $e){
    	$extends .= " LEFT JOIN {$e->table} ON {$e->linkfield} = {$e->field}";
    }
    
    $order = "";
    if(! empty($a->orderby)){
      $order = " ORDER BY " . $a->orderby;
    }
    
    $pre = str_replace("tbl_","",$a->table);
    $sql = "SELECT * FROM {$a->table} {$extends} WHERE {$a->field} = '{$id}' AND {$pre}_deleted IS NULL " . $order;
    if($res = $DBobject->wrappedSqlGet($sql)){
      foreach($res as $row){
        $r_array = array();
        foreach($row as $key=>$field){
          $r_array["{$key}"] = $field;
        }
        
        foreach($a->associated as $as){
          $r_array["{$as->name}"] = $this->getAssociated($as,$row["{$as->linkfield}"]);
        }
        $results[] = $r_array;
      }
    }
    return $results;
  }

  function getListingList($parent_id = '0') {
    global $SMARTY,$DBobject;
    $records = array();
    
    $order = " ORDER BY listing_order, listing_name, listing_published ASC";
    if(! empty($this->CONFIG_OBJ->orderby)){
      $order = " ORDER BY " . $this->CONFIG_OBJ->orderby;
    }
    
    $extends = "";
    foreach($this->CONFIG_OBJ->extends as $e){
    	$extends .= " LEFT JOIN {$e->table} ON {$e->linkfield} = {$e->field}";
    }

    $typeSQL = '';
    if(!empty($this->CONFIG_OBJ->type_id->attributes()->exclusive)){
      $typeSQL = " AND listing_type_id = " . intval($this->CONFIG_OBJ->type_id);
    }
    
    $sql = "SELECT * FROM {$this->DBTABLE} {$extends}
		WHERE listing_parent_id = :pid AND 
		tbl_listing.listing_deleted IS NULL AND tbl_listing.listing_id IS NOT NULL " . ($this->WHERE != ''?"AND {$this->WHERE} ":" ") . $typeSQL . $order;
    
    $params = array(
        ":pid"=>$parent_id
    );
    
    if($res = $DBobject->wrappedSqlGet($sql,$params)){ 
      foreach($res as $key=>$val){
        $subs = array();
        if(intval($val['listing_published']) == 1 && $val['listing_object_id'] > 0){
          $subs = $this->getListingList($val['listing_object_id']);
        }
        if($val['listing_type_id'] == $this->TYPE_ID){
          $p_url = '/';
          if(intval($val['listing_published']) == 0){
            $p_url = '/draft/';
          }
          foreach($this->CONFIG_OBJ->associated as $a){
          	if ($a->attributes()->inlist) {
          		$val["{$a->name}"] = $this->getAssociated($a, $val["{$a->linkfield}"]);
          	}
          }
          $val["title"] = $val['listing_name'];
          $val["order"] = $val['listing_order'];
          $val["id"] = $val['listing_id'];
          $val["published"] = $val['listing_published'];
          $val["preview_url"] = $p_url . self::getUrl($val['listing_parent_id'],$val['listing_published'], $val['listing_url']) ;
          $val["preview_url"] = preg_match("/^\//",$val["preview_url"])?$val["preview_url"]:'/'.$val["preview_url"];
          $val["url"] = "/admin/edit/{$this->CONFIG_OBJ->url}/{$val['listing_id']}";
          $val["url_delete"] = "/admin/delete/{$this->CONFIG_OBJ->url}/{$val['listing_id']}";
          $val["subs"] = $subs;
          $records["l{$val['listing_id']}"] = $val;
        }else{
          $val["title"] = $val['listing_name'];
          $val["order"] = $val['listing_order'];
          $val["id"] = $val['listing_id'];
          $val["published"] = $val['listing_published'];
          $val["subs"] = $subs;
          $records["c{$val['listing_id']}"] = $val;
        }
      }
    }
    return $records;
  }

  function deleteListing($id) {
    global $DBobject;
    $sql = "UPDATE {$this->DBTABLE} SET listing_deleted = NOW() WHERE listing_id = '{$id}' ";
    if($DBobject->executeSQL($sql)){
      global $DELETED;
      $_SESSION['notice'] = $DELETED;
      saveInLog('Delete', 'tbl_listing', $id);
      return true;
    }
    return false;
  }

  function getUrl($id, $published = 1, $url = '') {
    global $DBobject;
    
    $data = '/'.$url;
    $sql = "SELECT listing_url, listing_parent_id FROM tbl_listing WHERE listing_object_id = :id AND listing_deleted IS NULL ORDER BY listing_published = :published";
    if($res = $DBobject->executeSQL($sql,array(
        ':id'=>$id,
        ':published'=>$published
    ))){
    	if(!empty($res[0]['listing_parent_id']) && intval($res[0]['listing_parent_id'])>0 && !empty($res[0]['listing_url'])){
    		$data = self::getUrl($res[0]['listing_parent_id'],1,$res[0]['listing_url']. '/' . $url);
    	}else{ 
      	$data = $res[0]['listing_url'] . '/' . $url;
    	}
    }
    return $data;
  }
  
  function getLog($a, $id) {
  	global $SMARTY,$DBobject;
  	$results = array();
  
  	if((string) $a->id != (string) $a->field){
  		$sql = "SELECT {$a->field} FROM {$a->table} WHERE {$a->id} = :id" ;
  		$parent = $DBobject->wrappedSqlGet($sql, array(':id'=> $id));
  		 
  		$sql = "SELECT tbl_log.*, {$a->id}, admin_name FROM tbl_log LEFT JOIN tbl_admin ON admin_id = log_admin_id LEFT JOIN {$a->table} ON {$a->id} = log_record_id WHERE log_record_table = :table AND log_deleted IS NULL AND {$a->field} = :fid ORDER BY log_created DESC";
  		$results = $DBobject->wrappedSqlGet($sql, array(':table'=> $a->table, ':fid'=> $parent[0]["{$a->field}"]));
  	
  	}else{
  		$sql = "SELECT tbl_log.*, tbl_admin.admin_name FROM tbl_log LEFT JOIN tbl_admin ON admin_id = log_admin_id WHERE log_record_table = :table AND log_record_id = :id AND log_deleted IS NULL ORDER BY log_created DESC";
	  	$results = $DBobject->wrappedSqlGet($sql, array(':table'=> $a->table, ':id'=> $id) );
  	}
  	return $results;
  }
  
}
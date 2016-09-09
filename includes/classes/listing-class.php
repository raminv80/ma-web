<?php
class ListClass{
  protected $CONFIG_OBJ;
  protected $DBTABLE;
  // URL value passed into constructor
  protected $URL;
  // SQL ELEMENTS
  protected $P_TBL = "tbl_listing";
  protected $TBL = "tbl_listing";
  protected $SELECT = "*";
  protected $TABLES = "";
  protected $WHERE = "";
  protected $ORDERBY = "";
  protected $GROUPBY = "";
  protected $LIMIT = "";
  // SET OF DATA LOADED
  protected $DATA;
  protected $ID;


  function __construct($_URL, $_CONFIG_OBJ){
    global $SMARTY, $DBobject;
    $this->URL = $_URL;
    $this->CONFIG_OBJ = $_CONFIG_OBJ;
    $this->TBL = !empty($_CONFIG_OBJ->sqltable)? $_CONFIG_OBJ->sqltable : $this->P_TBL;
  }


  function Load($_ID = null, $_PUBLISHED = 1){
    global $SMARTY, $DBobject;
    $template = "";
    $data = "";
    // Split the URL
    $_url = ltrim(rtrim($this->URL, '/'), '/');
    
    $this->ID = $this->ChkCache($_url, $_PUBLISHED);
    if(empty($this->ID)){
      $this->ID = $_ID;
    }
    $tree_ID = $this->ID;
    if(!empty($this->CONFIG_OBJ->pageID) && $this->CONFIG_OBJ->pageID == $this->ID){
      $tree_ID = $_ID;
    }
    
    if(!empty($tree_ID)){
      $data = $this->LoadTree($tree_ID, 0, 0, 1);
      $SMARTY->assign('data', unclean($data));
    } else{
      return null;
    }
    
    $where = "";
    if(!empty($this->CONFIG_OBJ->table->where)){
      $where = " AND " . $this->CONFIG_OBJ->table->where;
    }
    
    $extends = "";
    foreach($this->CONFIG_OBJ->table->extends as $a){
      $pre = str_replace("tbl_", "", $a->table);
      $extends .= " LEFT JOIN {$a->table} ON {$a->linkfield} = {$a->field}";
      $where .= " AND {$pre}_deleted IS NULL";
    }
    
    $sql = "SELECT * FROM {$this->TBL} {$extends} WHERE {$this->TBL}.listing_object_id = :id AND {$this->TBL}.listing_deleted IS NULL {$where} ORDER BY {$this->TBL}.listing_published = :published DESC ";
    $params = array(
        ":id" => $this->ID, 
        ":published" => $_PUBLISHED 
    );
    
    if($res = $DBobject->wrappedSqlGet($sql, $params)){
      foreach($res[0] as $key => $val){
        $SMARTY->assign($key, unclean($val));
      }
      
      $bdata = $this->LoadBreadcrumb($res[0]['listing_object_id'], null, 1);
      $SMARTY->assign("breadcrumbs", $bdata);
      
      $p_data = $this->LoadParents($res[0]['listing_parent_id'], 1);
      $SMARTY->assign("listing_parent", unclean($p_data));
      
      foreach($this->CONFIG_OBJ->table->associated as $a){
        $t_data = $this->LoadAssociated($a, $res[0]["{$a->linkfield}"]);
        $SMARTY->assign("{$a->name}", unclean($t_data));
      }
      foreach($this->CONFIG_OBJ->table->additional as $a){
        $this->LoadAdditional($a, $res[0]["{$a->linkfield}"]);
      }
    } else{
      return null;
    }
    
    // ------------- LOAD OPTIONS FOR SELECT INPUTS --------------
    foreach($this->CONFIG_OBJ->table->options->field as $f){
      if($f->attributes()->recursive){
        $options = $this->getOptionsCatTree($f, $f->attributes()->parent_root);
      } else{
        $pre = str_replace("tbl_", "", $f->table);
        $extraArr = array();
        foreach($f->extra as $xt){
          $extraArr[] = (string)$xt;
        }
        $extraStr = !empty($extraArr)? "," . implode(',', $extraArr) : "";
        $sql = "SELECT {$f->id},{$f->reference} {$extraStr} FROM {$f->table} WHERE {$pre}_deleted IS NULL " . ($f->where != ''? "AND {$f->where} " : "") . " " . ($f->orderby != ''? " ORDER BY {$f->orderby} " : "");
        if($res2 = $DBobject->wrappedSqlGet($sql)){
          $options = array();
          foreach($res2 as $row){
            $options[$row["{$f->id}"]]['id'] = $row["{$f->id}"];
            $options[$row["{$f->id}"]]['value'] = $row["{$f->reference}"];
            foreach($extraArr as $xt){
              $options[$row["{$f->id}"]]["{$xt}"] = $row["{$xt}"];
            }
          }
        }
      }
      $SMARTY->assign("{$f->name}", unclean($options));
    }
    $this->loadPageAdditional($this->CONFIG_OBJ);
    
    $template = $this->CONFIG_OBJ->template;
    foreach($this->CONFIG_OBJ->template as $t){
      if(!empty($res[0]['listing_type_id']) && $t->attributes()->typeid == $res[0]['listing_type_id']){
        $template = $t;
        break;
      }
    }
    
    if(empty($data) && !empty($this->CONFIG_OBJ->table->template) && ($tree_ID == $this->ID)){
      $template = $this->CONFIG_OBJ->table->template;
      foreach($this->CONFIG_OBJ->table->template as $t){
        if(!empty($res[0]['listing_type_id']) && $t->attributes()->typeid == $res[0]['listing_type_id']){
          $template = $t;
          break;
        }
      }
    }
    
    return $template;
  }


  private function loadPageAdditional($_conf){
    global $CONFIG, $_PUBLISHED, $SMARTY, $_request;
    if(!empty($_conf)){
      foreach($_conf->additional as $ad){
        
        $tag = (string)$ad->tag;
        $name = (string)$ad->name;
        $data = (string)$ad->data;
        foreach($CONFIG->$tag as $lp){
          if($lp->attributes()->name == $name){
            foreach($ad->update as $up){
              $child = (string)$up->child;
              $parent = (string)$up->parent;
              if(!empty($child)){
                $path = $parent . '->' . $child;
              } else{
                $path = $parent;
              }
              $value = (string)$up->value;
              if(!empty($lp->$path)){
                $lp->$path = $value;
              } else{
                if(!empty($child)){
                  $lp->$parent->addChild($child, $value);
                } else{
                  $lp->addChild($parent, $value);
                }
              }
            }
            
            $class = (string)$lp->file;
            $obj = new $class($_request["arg1"], $lp);
            $data2 = $obj->LoadTree($lp->root_parent_id);
            $SMARTY->assign($data, unclean($data2));
            break 1;
          }
        }
      }
    }
  }


  private function cached($type, $timestamp, $func, $p = null){
    global $DBobject;
    $data = array();
    $sql = "SELECT * FROM cache_data WHERE cache_type = :type";
    $params = array(
        ":type" => $type 
    );
    if($res = $DBobject->wrappedSqlGet($sql, $params)){
      if(strtotime($res[0]['cache_modified']) == strtotime($timestamp)){
        $data = unserialize($res[0]['cache_val']);
      } else{
        $data = $this->$func($p);
        $sql = "UPDATE cache_data SET cache_val = :data, cache_modified = :mod WHERE cache_id = :id";
        $params = array(
            ":id" => $res[0]['cache_id'], 
            ":data" => serialize($data), 
            ":mod" => $timestamp 
        );
        $DBobject->wrappedSql($sql, $params);
      }
    } else{
      $data = $this->$func($p);
      $sql = "INSERT INTO cache_data (cache_type, cache_val, cache_modified) VALUES (:type,:data,:mod)";
      $params = array(
          ":type" => $type, 
          ":data" => serialize($data), 
          ":mod" => $timestamp 
      );
      $DBobject->wrappedSql($sql, $params);
    }
    return $data;
  }


  function LoadParents($_ID, $_PUBLISHED = 1){
    global $SMARTY, $DBobject;
    $data = array();
    $sql = "SELECT {$this->TBL}.* FROM {$this->TBL} WHERE {$this->TBL}.listing_object_id = :id AND {$this->TBL}.listing_deleted IS NULL ORDER BY {$this->TBL}.listing_published = :published DESC ";
    $params = array(
        ":id" => $_ID, 
        ":published" => $_PUBLISHED 
    );
    if($res = $DBobject->wrappedSqlGet($sql, $params)){
      $data = $res[0];
      foreach($this->CONFIG_OBJ->table->associated as $a){
        $data["{$a->name}"] = $this->LoadAssociated($a, $data["{$a->linkfield}"]);
      }
      // $data['listing_parent'] = $this->LoadParents($res[0]['listing_parent_id']);
    }
    return $data;
  }


  /**
   * Checks if the URL exists in the Cache table and returns the Page Record ID for the
   * url page.
   * Returns FALSE if URL does not exist in the list of all pages.
   *
   * @param String $_url
   *          URL without the leading / or domain components
   * @param Boolean $_PUBLISHED
   *          1 or 0 representing True or False
   * @param Boolean $typeflag
   *          Boolean flag changes return to page type instead of id
   * @return Page Record ID or Page Record Type
   */
  function ChkCache($_url, $_PUBLISHED, $typeflag = false){
    global $SMARTY, $DBobject;
    $args = explode('/', $_url);
    $a = end($args);
    $sql = "SELECT cache_record_id, cache_type FROM cache_{$this->TBL} WHERE cache_url = :url AND EXISTS (SELECT listing_id FROM {$this->TBL} WHERE listing_object_id = cache_record_id AND listing_published = :published AND listing_deleted IS NULL)";
    $params = array(
        ":url" => $_url, 
        ":published" => $_PUBLISHED 
    );
    try{
      $row = $DBobject->wrappedSqlGetSingle($sql, $params);
      if(empty($row)){
        $sql2 = "SELECT listing_url FROM {$this->TBL} WHERE listing_url = :url AND listing_published = :published";
        $params2 = array(
            ":url" => $a, 
            ":published" => $_PUBLISHED 
        );
        if($res = $DBobject->wrappedSqlGet($sql2, $params2)){
          self::BuildCache();
          $row = $DBobject->wrappedSqlGetSingle($sql, $params);
        }
      }
    }
    catch(Exception $e){
      $sql2 = "SELECT listing_url FROM {$this->TBL} WHERE listing_url = :url AND listing_published = :published";
      $params2 = array(
          ":url" => $a, 
          ":published" => $_PUBLISHED 
      );
      if($res = $DBobject->wrappedSqlGet($sql2, $params2)){
        self::BuildCache();
        $row = $DBobject->wrappedSqlGetSingle($sql, $params);
      }
    }
    if(!empty($row)){
      if($typeflag){
        return $row['cache_type'];
      } else{
        return $row['cache_record_id'];
      }
    }
    return false;
  }
  
  // TODO: THIS NEEDS TO BE REVISED TO SUPPORT HAVING 2 URLS FOR THE SAME LISTING_OBJECT_ID IN THE EVENT THAT THE URL VALUE IS CHANGING.
  function BuildUrl($_id, &$url, $_PUBLISHED = 1){
    global $DBobject;
    $sql = "SELECT * FROM {$this->TBL} WHERE listing_object_id = :id AND listing_published = :published AND listing_deleted IS NULL ORDER BY listing_modified DESC";
    $params = array(
        ":id" => $_id, 
        ":published" => $_PUBLISHED 
    );
    if($res = $DBobject->wrappedSql($sql, $params)){
      if(!empty($res[0]['listing_parent_id']) && $res[0]['listing_parent_id'] > 0 && $res[0]['listing_parent_id'] != $_id){ // category_listing_id
        $url = $res[0]['listing_url'] . '/' . $url;
        return self::BuildUrl($res[0]['listing_parent_id'], $url); // category_listing_id
      } else{
        $url = $res[0]['listing_url'] . '/' . $url;
        $url = ltrim(rtrim($url, '/'), '/');
        return true;
      }
    } else{
      return false;
    }
  }


  function BuildCache(){
    global $SMARTY, $DBobject;
    
    $sql[0] = "CREATE TABLE IF NOT EXISTS `cache_{$this->TBL}` (
		`cache_id` INT(11) NOT NULL AUTO_INCREMENT,
		`cache_record_id` INT(11) DEFAULT NULL,
		`cache_url` VARCHAR(255) DEFAULT NULL,
		`cache_type` INT(2) DEFAULT NULL,
		`cache_published` TINYINT(1) DEFAULT NULL,
		`cache_created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
		`cache_modified` DATETIME DEFAULT NULL,
		`cache_deleted` DATETIME DEFAULT NULL,
		PRIMARY KEY (`cache_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $DBobject->wrappedSql($sql[0]);
    
    $sql[3] = "TRUNCATE cache_{$this->TBL};";
    $sql[1] = "SELECT DISTINCT(listing_parent_id) FROM {$this->TBL} WHERE listing_deleted IS NULL AND listing_parent_id IS NOT NULL";
    $res = $DBobject->wrappedSql($sql[1]);
    
    $ids = array();
    foreach($res as $row){
      $ids[] = "listing_object_id = {$row['listing_parent_id']}";
    }
    $sql_r = "SELECT listing_object_id AS id, listing_parent_id,listing_url, listing_published FROM {$this->TBL} WHERE (" . implode(" OR ", $ids) . ") AND listing_deleted IS NULL GROUP BY CONCAT(listing_object_id,':',listing_url) ORDER BY listing_published = 1"; // GET DISTINCT object_id + url
    $res = $DBobject->wrappedSql($sql_r);
    foreach($res as $row){
      $id = $row['id'];
      $url = "";
      $published = $row['listing_published'];
      if(!self::BuildUrl($row['id'], $url, $published)){
        continue;
      }
      $sql_c[] = "INSERT INTO cache_{$this->TBL} (cache_record_id,cache_published,cache_url,cache_type,cache_modified) SELECT listing_object_id,listing_published, CONCAT('{$url}','/',listing_url), listing_type_id, now() FROM {$this->TBL} WHERE listing_parent_id = {$id} AND listing_deleted IS NULL";
    }
    $sql_c[] = "INSERT INTO cache_{$this->TBL} (cache_record_id,cache_published,cache_url,cache_type,cache_modified) SELECT listing_object_id,listing_published, listing_url, listing_type_id, now() FROM {$this->TBL} WHERE (listing_parent_id = 0 OR listing_parent_id IS NULL) AND listing_deleted IS NULL";
    
    $DBobject->wrappedSql($sql[3]);
    foreach($sql_c as $s){
      $DBobject->wrappedSql($s);
    }
  }
  
  // FOR REFERENCE
  /*
   * function BuildUrl($_id, &$url) { global $DBobject; $sql = "SELECT * FROM {$this->TBL} WHERE listing_object_id = :id ORDER BY listing_modified DESC"; $params = array ( ":id" => $_id ); if ($res = $DBobject->wrappedSql ( $sql, $params )) { if (! empty ( $res [0] ['listing_deleted'] ) || $res [0] ['listing_published'] != '1') { return false; } if (! empty ( $res [0] ['listing_parent_id'] )) { // category_listing_id $url = $res [0] ['listing_url'] . '/' . $url; return self::BuildUrl ( $res [0] ['listing_parent_id'], $url ); // category_listing_id } else { $url = $res [0] ['listing_url'] . '/' . $url; $url = ltrim ( rtrim ( $url, '/' ), '/' ); return true; } } else { return false; } }
   */
  function LoadBreadcrumb($_id, $_subs = null, $_PUBLISHED = 1){
    global $CONFIG, $SMARTY, $DBobject;
    $data = array();
    $sql = "SELECT * FROM {$this->TBL} WHERE {$this->TBL}.listing_object_id = :id AND {$this->TBL}.listing_deleted IS NULL";
    $params = array(
        ":id" => $_id 
    );
    if($res = $DBobject->wrappedSql($sql, $params)){
      $data[$res[0]['listing_id']]["title"] = ucfirst(unclean($res[0]['listing_name']));
      $data[$res[0]['listing_id']]["url"] = $res[0]['listing_url'];
      $data[$res[0]['listing_id']]["subs"] = $_subs;
      if(!empty($res[0]['listing_parent_id']) && $res[0]['listing_parent_id'] != 0){
        $sql2 = "SELECT * FROM {$this->TBL} WHERE {$this->TBL}.listing_object_id = :cid AND {$this->TBL}.listing_deleted IS NULL ORDER BY {$this->TBL}.listing_published = :published DESC";
        $params2 = array(
            ":cid" => $res[0]['listing_parent_id'], 
            ":published" => $_PUBLISHED 
        );
        if($res2 = $DBobject->wrappedSql($sql2, $params2)){
          $data = self::LoadBreadcrumb($res2[0]['listing_object_id'], $data, $_PUBLISHED);
        }
      }
    }
    return $data;
  }


  function LoadMenu($_curPageId = 0){
    global $DBobject;
    
    $data = array();
    //GET MENU LOCATIONS
    $sql = "SELECT menu_location FROM tbl_menu WHERE menu_deleted IS NULL GROUP BY menu_location";
    if($locations = $DBobject->executeSQL($sql, $params)){
      foreach($locations as $loc){
        $data["{$loc['menu_location']}"] = $this->LoadMenuList($_curPageId, $loc['menu_location']);
      }
    }
    return $data;
  }
  
  
  protected function LoadMenuList($_curPageId = 0, $_loc = '', $_pid = 0){
    global $DBobject;
    
    $data = array();
    $selected = 0;
    //GET LIST
    $sql = "SELECT menu_id, menu_name, menu_parent_id, menu_listing_id, menu_external, menu_icon, menu_type, menu_order FROM tbl_menu WHERE menu_deleted IS NULL AND menu_parent_id = :pid AND menu_location = :loc ORDER BY menu_order, menu_name";
    $params = array(
        ":pid" => $_pid, 
        ":loc" => $_loc 
    );
    if($res = $DBobject->executeSQL($sql, $params)){
      foreach($res as $key => $val){
        $data[$key]['id'] = $val['menu_id'];
        $data[$key]['title'] = unclean($val['menu_name']);
        $data[$key]['type'] = $val['menu_type'];
        $data[$key]['icon'] = $val['menu_icon'];
        $data[$key]['url'] = ($val['menu_type'] == 3) ? $val['menu_external'] : ($this->getFullUrl($val['menu_listing_id']));
        $data[$key]['selected'] = (!empty($val['menu_listing_id']) && $val['menu_listing_id'] == $_curPageId)? 1 : 0;
        
        //GET SUB LIST
        $data[$key]['subs'] = array();
        if(!empty($val['menu_id'])){
          $data[$key]['subs'] = $this->LoadMenuList($_curPageId, $_loc, $val['menu_id']);
          if($data[$key]['selected'] == 0 && !empty($data[$key]['subs'])){
            $data[$key]['selected'] = $data[$key]['subs']['selected'];
          }
        }
      }
    }
    return array('list'=>$data, 'selected'=>$selected);
  }
  


  function LoadTree($_cid = 0, $level = 0, $count = 0, $_PUBLISHED = 1){
    global $CONFIG, $SMARTY, $DBobject;
    
    $data = array();
    if(!empty($this->CONFIG_OBJ->depth) && intval($this->CONFIG_OBJ->depth) > 0 && intval($this->CONFIG_OBJ->depth) <= $level){
      return $data;
    }
    
    $typeArr = array();
    foreach($this->CONFIG_OBJ->type as $t){
      $typeArr[] = (string)$t;
    }
    if(!empty($typeArr)){
      $typeArr[] = '0';
    }
    
    foreach($typeArr as $t){
      // GET THE TOP LEVEL CATEGORY (IF ANY) WHICH THIS OBJECT IS LINKED TOO
      $filter = "";
      $_default_filter = "";
      $filter_params = array();
      $f_count = 1;
      if(!empty($this->CONFIG_OBJ->filter)){
        foreach($this->CONFIG_OBJ->filter as $f){
          if($f->attributes()->default == 1){
            if(empty($f->value)){
              continue;
            }
            // Filter sql based on config only
            $_default_filter = " AND " . (string)$f->value;
            continue;
          }
          
          $fieldname = (string)$f->attributes()->name;
          $value = $_REQUEST[$fieldname];
          if(empty($value)) $value = $_POST[$fieldname];
          if(!empty($value)){
            if($f->attributes()->sqlscript == 1){
              if(empty($f->value)){
                continue;
              }
              $filter .= " AND " . (string)$f->value;
              continue;
            }
            if(empty($f->field) || empty($f->operator)){
              continue;
            }
            if(is_array($value)){
              $tmpField = array();
              foreach($value as $val){
                if(!empty($val)) continue;
                $tmpField[] = "{$f->field} {$f->operator} :filter{$f_count}";
                $filter_params[":filter{$f_count}"] = $val;
                $f_count++;
              }
              $filter .= " AND ( " . implode(' OR ', $tmpField) . " )";
            } else{
              $filter .= " AND {$f->field} {$f->operator} :filter{$f_count}";
              $filter_params[":filter{$f_count}"] = $value;
              $f_count++;
            }
            continue;
          }
        }
      }
      if(empty($filter) && !empty($_default_filter)){
        $filter = $_default_filter;
      }
      if(!empty($filter)){
        $this->CONFIG_OBJ->limit = 0;
      }
      $order = " ORDER BY {$this->TBL}.listing_order ASC";
      if(!empty($this->CONFIG_OBJ->orderby)){
        $order = " ORDER BY " . $this->CONFIG_OBJ->orderby;
      }
      $where = "";
      if(!empty($this->CONFIG_OBJ->where)){
        $where = " AND " . $this->CONFIG_OBJ->where;
      }
      if(!empty($this->CONFIG_OBJ->limit) && (empty($this->CONFIG_OBJ->limit->attributes()->level) || intval($this->CONFIG_OBJ->limit->attributes()->level) <= $level)){
        $limit = " LIMIT " . $this->CONFIG_OBJ->limit;
      }
      $typeIdSQL = "";
      $typeId_params = array();
      if(!empty($t)){
        $typeId_params[":type"] = $t;
        $typeIdSQL = " AND {$this->TBL}.listing_type_id = :type ";
      }
      $extends = "";
      foreach($this->CONFIG_OBJ->table->extends as $a){
        $pre = str_replace("tbl_", "", $a->table);
        $extends .= " LEFT JOIN {$a->table} ON {$a->linkfield} = {$a->field}"; // AND article_deleted IS NULL";
        $where .= " AND {$pre}_deleted IS NULL";
      }
      $params = array(
          ":published" => $_PUBLISHED 
      );
      if($this->CONFIG_OBJ->attributes()->ignoreparent == 1 && $_cid == 0){} else{
        $catsql = " AND {$this->TBL}.listing_parent_id = :cid ";
        $params[":cid"] = $_cid;
      }
      $sql = "SELECT * FROM {$this->TBL} {$extends} WHERE {$this->TBL}.listing_deleted IS NULL {$catsql}{$typeIdSQL} AND {$this->TBL}.listing_published = :published" . $where . $filter . $order . $limit;
      
      $params = array_merge($params, $filter_params);
      if(!empty($typeIdSQL)){
        $params = array_merge($params, $typeId_params);
      }
      
      if($res = $DBobject->wrappedSql($sql, $params)){
        foreach($res as $row){
          $data[$t]["{$row['listing_object_id']}"] = unclean($row);
          foreach($this->CONFIG_OBJ->table->associated as $a){
            if($a->attributes()->listing){
              $data[$t]["{$row['listing_object_id']}"]["{$a->name}"] = $this->LoadAssociated($a, $row["{$a->linkfield}"], true);
            }
          }
          $subs = self::LoadTree($row['listing_object_id'], $level + 1, $count, $_PUBLISHED);
          $data[$t]["{$row['listing_object_id']}"]['listings'] = $subs;
        }
      }
    }
    return $data;
  }


  function getOptionsCatTree($f, $pid){
    global $SMARTY, $DBobject;
    $results = array();
    
    $pid = empty($pid)? 0 : $pid;
    $pre = str_replace("tbl_", "", $f->table);
    $extraArr = array();
    foreach($f->extra as $xt){
      $extraArr[] = (string)$xt;
    }
    $extraStr = !empty($extraArr)? "," . implode(',', $extraArr) : "";
    $sql = "SELECT {$f->id}, {$f->reference} {$extraStr} FROM {$f->table} WHERE {$pre}_deleted IS NULL AND {$pre}_parent_id = {$pid} " . ($f->where != ''? "AND {$f->where} " : "") . ($f->orderby != ''? " ORDER BY {$f->orderby} " : "");
    if($res = $DBobject->wrappedSqlGet($sql)){
      foreach($res as $row){
        $results[$row["{$f->id}"]]['id'] = $row["{$f->id}"];
        $results[$row["{$f->id}"]]['value'] = $row["{$f->reference}"];
        foreach($extraArr as $xt){
          $results[$row["{$f->id}"]]["{$xt}"] = $row["{$xt}"];
        }
        $results[$row["{$f->id}"]]['subs'] = self::getOptionsCatTree($f, $row["{$f->id}"]);
      }
    }
    return $results;
  }


  function LoadAssociated($a, $id){
    global $CONFIG, $SMARTY, $DBobject;
    $t_data = array();
    $group = "";
    $order = "";
    $limit = "";
    $where = "";
    if(!empty($a->groupby)){
      $group = " GROUP BY " . $a->groupby;
    }
    if(!empty($a->orderby)){
      $order = " ORDER BY " . $a->orderby;
    }
    if(!empty($a->limit)){
      $limit = " LIMIT " . $a->limit;
    }
    if(!empty($a->where)){
      $where = " AND " . $a->where;
    }
    $extends = "";
    foreach($a->extends as $e){
      $pre = str_replace("tbl_", "", $e->table);
      $extends .= " LEFT JOIN {$e->table} ON {$e->linkfield} = {$e->field}";
      $where .= " AND {$pre}_deleted IS NULL";
    }
    $t_data = array();
    $pre = str_replace("tbl_", "", $a->table);
    $sql = "SELECT * FROM {$a->table} {$extends} WHERE {$a->field} = :id AND {$pre}_deleted IS NULL " . $where . " " . $group . " " . $order . " " . $limit;
    $params = array(
        ':id' => $id 
    );
    if($res2 = $DBobject->wrappedSqlGet($sql, $params)){
      foreach($res2 as $row2){
        foreach($a->associated as $asc){
          if(!empty($a->linkfield)){
            $row2["{$asc->name}"] = self::LoadAssociated($asc, $row2["{$asc->linkfield}"]);
          }
        }
        $t_data[] = $row2;
      }
    }
    return $t_data;
  }


  function LoadAdditional($ad){
    global $CONFIG, $SMARTY, $DBobject;
    $t_data = array();
    $tag = (string)$ad->tag;
    $name = (string)$ad->name;
    $data = (string)$ad->data;
    foreach($CONFIG->$tag as $lp){
      if($lp->attributes()->name == $name){
        $class = (string)$lp->file;
        $obj = new $class('', $lp);
        $data2 = $obj->LoadTree($lp->root_parent_id);
        $SMARTY->assign($data, unclean($data2));
        break 1;
      }
    }
    return true;
  }


  function LoadAssociatedByTag($cfg){
    global $CONFIG, $SMARTY, $DBobject;
    
    foreach($cfg->table->tags as $a){
      $preObjTbl = str_replace("tbl_", "", $a->object_table);
      $sql = "SELECT {$a->object_value} AS VALUE FROM {$a->object_table} WHERE {$preObjTbl}_id = :id AND {$preObjTbl}_deleted IS NULL AND {$preObjTbl}_published = 1 ";
      $params = array(
          ":id" => $this->ID 
      );
      if($res = $DBobject->wrappedSqlGet($sql, $params)){
        
        $group = "";
        $order = "";
        $limit = "";
        $where = "";
        if(!empty($a->groupby)){
          $group = " GROUP BY " . $a->groupby;
        }
        if(!empty($a->orderby)){
          $order = " ORDER BY " . $a->orderby;
        }
        if(!empty($a->limit)){
          $limit = " LIMIT " . $a->limit;
        }
        if(!empty($a->where)){
          $where = " AND " . $a->where;
        }
        
        $pre = str_replace("tbl_", "", $a->table);
        $sql = "SELECT {$a->object_table}.* FROM {$a->table} LEFT JOIN {$a->object_table} ON  {$preObjTbl}_id = {$pre}_object_id 
						WHERE {$pre}_object_table = :objTable AND {$pre}_value = :objValue  AND {$pre}_deleted IS NULL 
								AND {$preObjTbl}_deleted IS NULL AND {$preObjTbl}_published = 1 " . $where . " " . $group . " " . $order . " " . $limit;
        $params = array(
            ':objTable' => $a->object_table, 
            ':objValue' => $res[0]["VALUE"] 
        );
        
        $data = array();
        if($objs = $DBobject->wrappedSqlGet($sql, $params)){
          foreach($objs as $o){
            $data["{$o["{$preObjTbl}_id"]}"] = $o;
            $url = $o["{$preObjTbl}_url"];
            $this->BuildUrl($o["{$preObjTbl}_parent_id"], $url);
            $data["{$o["{$preObjTbl}_id"]}"]["absolute_url"] = $url;
            
            foreach($cfg->table->associated as $b){
              $data["{$o["{$preObjTbl}_id"]}"]["{$b->name}"] = $this->LoadAssociated($b, $o["{$b->linkfield}"]);
            }
          }
        }
        $SMARTY->assign("{$a->name}", unclean($data));
      }
    }
  }
  
  
  function getFullUrl($_oid, $_level = 0) {
    global $DBobject;
  
    $url = '';
    if(!empty($_oid) || $_level > 8){
      $sql = "SELECT listing_parent_id, listing_url FROM tbl_listing WHERE listing_deleted IS NULL AND listing_published = 1 AND listing_object_id = :oid LIMIT 1";
      if($res = $DBobject->wrappedSql($sql, array(":oid" => $_oid))){
        $url = "/{$res[0]['listing_url']}";
        if(!empty($res[0]['listing_parent_id'])){
          $url = $this->getFullUrl($res[0]['listing_parent_id'], $_level++) . $url;
        }
      }
    }
    return $url;
  }
}
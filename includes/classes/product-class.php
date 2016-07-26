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
    $sql = "SELECT * FROM {$this->DBPRODTABLE} {$extends} WHERE {$this->DBPRODTABLE_URL} = :url AND {$pre}_deleted IS NULL ORDER BY {$pre}_published = :published DESC LIMIT 1";
    $params = array(
        ":url" => $_url,
        ":published" => $_PUBLISHED
    );
    if($res = $DBobject->wrappedSqlGet($sql,$params)){
      $this->ID = $res[0]["{$this->DBPRODTABLE_KEY}"];
      foreach($res[0] as $key=>$val){
        $SMARTY->assign($key,unclean($val));
      }
      // ------------- LOAD ASSOCIATED TABLES --------------
      foreach($LOCALCONF->associated as $a){
        $t_data = $this->LoadAssociated($a,$res[0]["{$a->linkfield}"]);
        $SMARTY->assign("{$a->name}",$t_data);
      }
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
        $sql = "SELECT {$f->id},{$f->reference} {$extraStr} FROM {$f->table} WHERE {$pre}_deleted IS NULL " . ($f->where != ''?"AND {$f->where} ":"") . " " . ($f->orderby != ''?" ORDER BY {$f->orderby} ":"");
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
      $SMARTY->assign("{$f->name}",$options);
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
        $sql = "SELECT  {$a->object_value} AS VALUE  FROM {$a->object_table} WHERE {$preObjTbl}_id = :id AND {$preObjTbl}_deleted IS NULL AND {$preObjTbl}_published = 1 ";
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
					WHERE {$pre}_object_table = :objTable AND {$pre}_value = :objValue  AND {$pre}_deleted IS NULL
					AND {$preObjTbl}_deleted IS NULL AND {$preObjTbl}_published = 1 " . $where . " " . $group . " " . $order . " " . $limit;
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
 
}
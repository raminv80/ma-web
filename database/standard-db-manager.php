<?php
/*
 * DB manager class
 */

Class DBmanager{
	private $LAST_INSERTED_ID;
	 
	/**
	 * Enter description here ...
	 */
	function __construct(){
		global  $CONFIG;
		$server_db = $CONFIG->database->host;
		$username_db = $CONFIG->database->user;
		$password_db = $CONFIG->database->password;
		$name_db = $CONFIG->database->dbname;
		$link = @mysql_connect($server_db, $username_db, $password_db);
		if(!$link) {
		  echo mysql_error();
		  die('Could not connect to server');
		}
		if(!@mysql_select_db($name_db))	{
		  die("Could not select database");
		}
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $MySQL
	 * @return void|resource
	 */
	function executeSQL($MySQL , $params = array()){
		if(empty($MySQL)){
			return false;
		}
		//Prepare SQL
		foreach($params as $key => $val){
		  $val = clean($val);
		  if(preg_match("/^:/", $key)<1){$key = ":".$key;}
		  $MySQL = str_replace($key, "'{$val}'", $MySQL); 
		}
		$res = mysql_query($MySQL);
		if(mysql_error()) {
		  $err = mysql_error();
		  $trace = debug_backtrace();
		  $backtrace = parse_backtrace($trace);
		  $errMsg = logError($backtrace, $err, $MySQL);
		  header("Location: /404");
		  die();
		}
		$this->lastquery = $MySQL;
		
		$this->LAST_INSERTED_ID = mysql_insert_id();
		$result = array();
		while($row = mysql_fetch_assoc($res)){
		   $result[] = $row;
		}
		
// 	  $identity_res = mysql_query("SELECT @@IDENTITY AS id");
// 	  if($_temp_id = mysql_fetch_array($identity_res)){
// 	    $this->LAST_INSERTED_ID = $_temp_id;
// 	  }

		if($res === true){
			$result = true;
		} 
		return $result;
	}

	/**
	 * Enter description here ...
	 */
	function wrappedSqlIdentity() {
		return $this->LAST_INSERTED_ID;
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $sql
	 * @return Ambigous <void, resource>
	 */
	function wrappedSqlInsert( $sql , $params = array() ) {
		$result = $this->executeSQL($sql, $params );
		if(mysql_error()) {
			$err = mysql_error();
			$trace = debug_backtrace();
			$backtrace = parse_backtrace($trace);
			$errMsg = logError($backtrace, $err, $sql);
			header('Location: /404');
		}
		return $result;
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $sql
	 * @return string|boolean
	 */
	function wrappedSqlGetSingle( $sql , $params = array() ) {
		$result = $this->executeSQL($sql , $params );
		if(mysql_error()) {
			$err = mysql_error();
			$trace = debug_backtrace();
			$backtrace = parse_backtrace($trace);
			$errMsg = logError($backtrace, $err, $sql);
			header('Location: /404');
			die();
		}
		if($result){
			return $result[0];
		}
		else {
			return false;
		}
	}
	/**
	 * Enter description here ...
	 * @param unknown_type $sql
	 * @return multitype:|boolean
	 */
	function wrappedSqlGet( $sql , $params = array() ){
		$result = $this->executeSQL( $sql ,  $params);
		if(mysql_error()) {
			$err = mysql_error();
			$trace = debug_backtrace();
			$backtrace = parse_backtrace($trace);
			$errMsg = logError($backtrace, $err, $sql);
			header('Location: /404');
		}
		$sql_arr = array();
		if(!empty($result)) {
			return $result;
		}else{
			return false;
		}

	}

	/**
	 * Enter description here ...
	 * @param unknown_type $sql
	 * @return multitype:|boolean
	 */
	function wrappedSql( $sql, $params = array()  ){
		$result = $this->executeSQL($sql,$params);
		if(mysql_error()) {
			$err = mysql_error();
			$trace = debug_backtrace();
			$backtrace = parse_backtrace($trace);
			$errMsg = logError($backtrace, $err, $sql);
			header('Location: /404');
		}
		if(!empty($result)) {
			return $result;
		}else{
			return false;
		}

	}

	/**
	 * Enter description here ...
	 * @param unknown_type $table
	 * @return multitype:
	 */
	function ShowColumns( $table ){
		$sql =  "SHOW COLUMNS FROM ".$table;
		if($sql_res = $this->executeSQL($sql)){
			if(!empty($sql_res)) {

				return $result;
			}else {
				return false;
			}
		}
	}
	function ShowTables(){
		$sql = "SHOW TABLES";
		$arr = $this->wrappedSqlGet($sql);
		foreach ($arr as $table_a){
			foreach ($table_a as $table) {
				$tables[]=$table;
			}
		}
		return $tables;
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $string
	 * @return string
	 */
	function SQLdatetoOZformat($string){
		$arrray = explode("-", $string);
		$date = $arrray[2]."/".$arrray[1]."/".$arrray[0];
		return $date;
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $string
	 * @return string
	 */
	function SQLdatetimetoOzFormat($string){
		$date = explode(" ", $string);
		$newdate = SQLdatetoOZformat($date[0]);
		$newdate .=" ".$date[1];
		return $newdate;
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $sql
	 * @return boolean
	 */
	function StoreSql($sql){
		$lower_sql = strtolower($sql);
		if(strstr($lower_sql, 'insert')		||		strstr($lower_sql, 'update')){
			$log_user_id =$_SESSION["admin"]["id"]!=''?$_SESSION["admin"]["id"]:$_SESSION["user"]["id"];
			if($log_user_id ==''){$log_user_id=0;}
			$sql ="	INSERT INTO tbl_log
	            (
	            log_user_id,
	            log_user_type,
	            log_user_sql,
	            log_user_ip
	            )
				VALUES (
				'".$log_user_id."',
				'".($_SESSION["admin"]["id"]!=''?"ADMIN":"USER")."',
				'".addslashes($lower_sql)."',
				'".$_SERVER['REMOTE_ADDR']."') ";

			$result = mysql_query($sql);
			if(mysql_error()) {
				$err = mysql_error();
				$trace = debug_backtrace();
				$backtrace = parse_backtrace($trace);
				$errMsg = logError($backtrace, $err, $MySQL);
				die($errMsg);

			}
		}
		return true;
	}
	
	

}
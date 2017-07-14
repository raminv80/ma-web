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
		$dbConnString =  "mysql:host=" . $server_db . "; dbname=" .$name_db ;
		$this->PDO = new PDO($dbConnString, $username_db , $password_db);
		$error = $this->PDO->errorInfo();
		if(!empty($error[0])) {
			die(var_dump($error));
		}
		
		/*old connection  
		$link = @mysql_connect($server_db, $username_db, $password_db);
		if(!$link) {
			echo mysql_error();
			die('Could not connect to server');
		}
		if(!@mysql_select_db($name_db))	{
			die("Could not select database");
		}
		*/
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
		$this->queryresult = true;
		$this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if($STH = $this->PDO->prepare($MySQL)){
			foreach ($params as $key => &$val) {
	   			$STH->bindParam(":{$key}", $val);
			}
			//echo $MySQL;
			$execute_res = $STH->execute($params);
			if($execute_res === false) {
				$err = print_r($this->PDO->errorInfo(),1);
				$trace = debug_backtrace();
				$backtrace = parse_backtrace($trace);
				$errMsg = logError($backtrace, $err, $MySQL);
				$this->queryresult = false;
				//die($MySQL);
				header('Location: /404');
				die();
			} 
			$this->LAST_INSERTED_ID =  $this->PDO->lastInsertId();
			$this->lastquery = $MySQL;
			
			try{
				$result = $STH->fetchAll(PDO::FETCH_ASSOC);
				$this->lastresult = $result;
			}catch(Exception $e){
				$result = $execute_res;
			}
			return $result;
		}
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

	

}
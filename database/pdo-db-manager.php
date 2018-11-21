<?php
/*
 * DB manager class
 */

Class DBmanager{
	private $LAST_INSERTED_ID;
	 
	/**
	 * Enter description here ...
	 */
	function __construct($server_db, $name_db, $username_db, $password_db){
		$dbConnString =  "mysql:host=" . $server_db . "; dbname=" .$name_db ;
		$this->PDO = new PDO(
		    $dbConnString,
            $username_db ,
            $password_db
        );
		$error = $this->PDO->errorInfo();
		if(!empty($error[0])) {
            var_dump($error);
			die();
		}
        $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->PDO->query('SET SESSION sql_mode = ""');
	}

    /**
     * @param $MySQL
     * @param array $params
     *
     * @return array|bool
     */
	function executeSQL($MySQL , $params = []){
		if(empty($MySQL)){
			return false;
		}
		$this->queryresult = true;
		$this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if($STH = $this->PDO->prepare($MySQL)){
			foreach ((Array)$params as $key => &$val) {
	   			$STH->bindParam(":{$key}", $val);
			}
			//echo $MySQL;
			$execute_res = $STH->execute($params);
			if($execute_res === false) {
				$err = print_r($this->PDO->errorInfo(),1);
				$trace = debug_backtrace();
				$backtrace = parse_backtrace($trace);
				logError($backtrace, $err, $MySQL);
				$this->queryresult = false;
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
		return $result;
	}

	/**
	 * Enter description here ...
	 * @param unknown_type $sql
	 * @return string|boolean
	 */
	function wrappedSqlGetSingle( $sql , $params = array() ) {
		$result = $this->executeSQL($sql , $params );
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
				return $sql_res;
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

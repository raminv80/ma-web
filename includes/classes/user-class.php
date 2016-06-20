<?php

class UserClass {
	protected $DBobj;
	protected $site;
	protected $user_id;
	
	function __construct($_db = '', $_site = '') {
		global $DBobject, $SITE;
		
		$this->DBobj = empty($_db)?$DBobject:$_db;
		$this->site = empty($_site)?(empty($SITE)?'':$SITE):$_site;
		$this->user_id = 0;
	}
	

	/**
	 * Insert a new record in tbl_user and return associative array: ('id', 'gname', 'surname', 'email')
	 * On error return associative array: ('error')
	 * Require associative array: ('gname', 'surname', 'password', 'email')
	 *
	 * @param array $user
	 * @return array
	 */
	function Create($user, $overWriteExisting = false){
	
		if($res = $this->RetrieveByUsername($user['username'])){
			if($overWriteExisting){
				if($res['user_gname'] != $user['gname'] || $res['user_surname'] != $user['surname']){
					$sql = "UPDATE tbl_user SET user_gname = :gname, user_surname = :surname, user_email_promo = :email_promo, 
							user_sms_promo = :sms_promo, user_ip = :ip, user_browser = :browser WHERE user_id = :id";
					$params = array (
							":id" => $res['user_id'],
							":gname" => $user['gname'],
							":surname" => (empty($user['surname'])?'':$user['surname']),
							":email_promo" => (empty($user['want_email_promo'])?0:1),
							":sms_promo" => (empty($user['want_sms_promo'])?0:1),
							":ip" => $_SERVER['REMOTE_ADDR'],
							":browser" => $_SERVER['HTTP_USER_AGENT']
					);
					$this->DBobj->wrappedSql($sql, $params);
				}
				$this->user_id = $res['user_id'];
				return $result = array (
						"id" => $res['user_id'],
						"gname" => $user['gname'],
						"surname" => $user['surname'],
						"email" => $res['user_email'],
						"extra" => ($this->GetExtraUserFields())
				);
			}else{
				$this->user_id = 0;
				return array ('error' => "This email '{$user['username']}' has already been used.");
			}
		} else {
			 
			$temp_str = getPass($user['email'],$user['password']);
	
			$params = array (
					":username" => $user['username'],
					":gname" => $user['gname'],
					":surname" => (empty($user['surname'])?'':$user['surname']),
					":email" => $user['email'],
					":password" => $temp_str,
					":mobile" => $user['mobile'],
					":user_site" => $this->site,
					":email_promo" => (empty($user['want_email_promo'])?0:1),
					":sms_promo" => (empty($user['want_sms_promo'])?0:1),
					":ip" => $_SERVER['REMOTE_ADDR'],
					":browser" => $_SERVER['HTTP_USER_AGENT']
			);
	
			$sql = "INSERT INTO tbl_user (user_username, user_gname, user_surname, user_email, user_password, user_mobile, user_site, user_email_promo, user_sms_promo, user_ip, user_browser, user_created)
					 values ( :username, :gname, :surname, :email, :password, :mobile, :user_site, :email_promo, :sms_promo, :ip, :browser, now() )";
			if($this->DBobj->wrappedSql($sql, $params)){
				$userId =  $this->DBobj->wrappedSqlIdentity();
				$this->SetUnsubscribeToken($userId);
				$this->user_id = $userId;
				$result = array (
						"id" => $userId,
						"gname" => $user['gname'],
						"surname" => $user['surname'],
						"email" => $user['email'],
						"extra" => ($this->GetExtraUserFields())
				);
			} else {
				$this->user_id = 0;
				$result = array ('error' => 'There was a connection problem. Please, try again!');
			}
			return  $result;
		}
	}
	
    
	/**
	* Update password field of a specific record in tbl_user and return associative array: ('success') 
  * On error return associative array: ('error')
  * Require associative array: ( 'email', 'password', 'old_password')
  * 
  * @param array $data
	* @return array
	*/
	function UpdatePassword($data){
        
		$res = $this->Authenticate($data['email'], $data['old_password']);
		if($res['error']){
			return array ('error'=>'Incorrect old password.');
		}else{
	   	$temp_str = getPass($data['email'],$data['password']);
	   	$params = array (
	    			":id" => $res['id'],
	    			":password" => $temp_str,
	        	":ip" => $_SERVER['REMOTE_ADDR'],
	        	":browser" => $_SERVER['HTTP_USER_AGENT']
	    );
    	$sql = "UPDATE tbl_user SET user_password = :password, user_ip = :ip, user_browser = :browser WHERE user_id = :id ";
    	if($this->DBobj->wrappedSql($sql, $params)) {
    		return array ('success' => 'Password has been updated.');
    	} else {
    		return array ('error' => 'There was a connection problem. Please, try again!');
    	}
    } 
	}
    
    
	/**
	* Get a single non-deleted record from tbl_user given the username
	* On error return false
	* Require string: username
	* 
	* @param string $uname
	* @return mixed
	*/
  function RetrieveByUsername($uname){
    
		$sql = "SELECT * FROM tbl_user WHERE user_username = :uname AND user_deleted IS NULL";
		$res  = $this->DBobj->wrappedSql($sql, array(":uname" => $uname));
		return $res[0];
	}
    
    
	/**
  * Get a single non-deleted record from tbl_user given the user id
  * On error return false
  * Require int: user_id
  * 
	* @param int $id
  * @return array
  */
  function RetrieveById($id){
    
		$sql = "SELECT * FROM tbl_user WHERE user_id = :id AND user_deleted IS NULL";
		$res  = $this->DBobj->wrappedSql($sql, array(":id" => $id));
    return $res[0];
	}
    
    
	/**
  * Get a user record in tbl_user given the email/password and return associative array: ('id', 'gname', 'surname', 'email') 
  * On error return associative array: ('error')
  * Require strings:  email and password
  * 
  * @param string $email
  * @param string $pass
  * @return array
  */
  function Authenticate($email, $pass){
    	
		$user_arr = array();
    $temp_str = getPass($email,$pass);
    	
    $sql = "SELECT * FROM tbl_user WHERE user_deleted IS NULL AND user_username = :email AND user_password = :password";
		$params = array( 
			"email" => $email, 
    	"password" => $temp_str 
    );
    	
		if($res = $this->DBobj->wrappedSql($sql, $params)){
			$this->user_id = $res[0]["user_id"];
    	$user_arr["id"] = $res[0]["user_id"];
    	$user_arr["gname"] = $res[0]["user_gname"];
    	$user_arr["surname"] = $res[0]["user_surname"];
    	$user_arr["email"] = $res[0]["user_email"];
    	$user_arr["extra"] = $this->GetExtraUserFields();
    }else{
    	$this->user_id = 0;
    	$user_arr["error"] = "Wrong email or password";
    }
    return $user_arr;
	}
    
  
	/**
	* 
	* @param array $data
	* @return multitype:string
	*/
	function UpdateDetails($data){
    	
		if($res = $this->RetrieveByUsername($data['email'])){
			$params = array (
	    	":id" => $res['user_id'],
	    	":gname" => $data['gname'],
		    ":surname" => (empty($data['surname'])?'':$data['surname']),
	    	":ip" => $_SERVER['REMOTE_ADDR'],
	    	":browser" => $_SERVER['HTTP_USER_AGENT']
			);
			
			$where = '';
			if(!empty($data['mobile'])){
				$params["mobile"] = $data['mobile'];
				$where .= 'user_mobile = :mobile, ';
			}
			if($data['want_email_promo'] == 0 || $data['want_email_promo'] == 1){
				$params["email_promo"] = $data['want_email_promo'];
				$where .= 'user_email_promo = :email_promo, ';
			}
			if($data['want_sms_promo'] == 0 || $data['want_sms_promo'] == 1){
				$params["sms_promo"] = $data['want_sms_promo'];
				$where .= 'user_sms_promo = :sms_promo, ';
			}
			
			$sql = "UPDATE tbl_user SET {$where} user_gname = :gname, user_surname = :surname, user_ip = :ip, user_browser = :browser WHERE user_id = :id ";
			if($this->DBobj->wrappedSql($sql, $params)){
				$res2 = $this->RetrieveById($res['user_id']);
				$userArr = array (
						"id" => $res['user_id'],
						"gname" => $res2['user_gname'],
						"surname" => $res2['user_surname'],
						"email" => $res2['user_email'],
						"extra" => ($this->GetExtraUserFields())
						);
				return array ('success' => 'Your details have been updated.', 'user_record' => $userArr);
	    }
	  	return array ('error' => 'There was a connection problem. Please, try again!');
    }
  	return array('error' => "This email '{$data['email']}' does not exist in our database.");
	}
 
    
    
	/**
  * Reset the password token for password recovery
  * On error return associative array: ('error')
  * Require strings:  email
  *
  * @param string $email
  * @return array
  */
  function ResetPasswordToken($email){
    
		if($res = $this->RetrieveByUsername($email)){
			$newPass = genRandomString(10);
	    $temp_str = getPass($email,$newPass);
	    
	    $params = array (
	    	":id" => $res['user_id'],
	    	":token" => $temp_str,
	    	":ip" => $_SERVER['REMOTE_ADDR'],
	    	":browser" => $_SERVER['HTTP_USER_AGENT']
			);
			$sql = "UPDATE tbl_user SET user_token = :token, user_token_date = now() , user_ip = :ip, user_browser = :browser WHERE user_id = :id ";
			if($this->DBobj->wrappedSql($sql, $params)){
				return array ('success' => 'Your new password has been sent to the registered email address.',
					'token' =>$newPass,
					'user_gname' =>$res['user_gname']);
	    }
	    return array ('error' => 'There was a connection problem. Please, try again!');
		}
	  return array( 'error' => "Sorry, '{$email}' does not appear to be registered with this site. Check your email or please create an account.");
  }
    
    
	/**
  * Reset the password field given the email, token and new password. It returns associative array with user's details on success
	* On error return associative array: ('error')
  * Require strings:  email and password
  *
  * @param string $email
  * @return array
  */
	function ResetPassword($email, $userToken, $newPassword){
    	 
  	$error = "Sorry, '{$email}' does not appear to be registered with this site. Check your email or please create an account.";
    if($res = $this->RetrieveByUsername($email)){
			$token = getPass($email,$userToken);
    
			$sql = "SELECT user_id,IF(user_token_date>=DATE_SUB(NOW( ),INTERVAL 4 HOUR),0,1) AS expired FROM tbl_user WHERE user_token = :token";
			if($res2 = $this->DBobj->wrappedSql($sql, array(':token' => $token)) ){
    		if(empty($res2) || empty($res2[0]) || $res2[0]['expired'] == 1){
    			$error = 'This url has expired, please request a new reset password.';
    		}else{
					$temp_str = getPass($email, $newPassword);
    			$params = array (
    				":id" => $res2[0]['user_id'],
    				":password" => $temp_str,
    				":ip" => $_SERVER['REMOTE_ADDR'],
    				":browser" => $_SERVER['HTTP_USER_AGENT']
    			);
					$sql = "UPDATE tbl_user SET user_password = :password, user_ip = :ip,user_browser = :browser WHERE user_id = :id ";
    			if($this->DBobj->wrappedSql($sql, $params)){
    				$sql = "UPDATE tbl_user SET user_token = NULL, user_token_date = NULL WHERE user_id = :id "; //Reset password
    				$this->DBobj->wrappedSql($sql, array("id"=>$res2[0]['user_id']));
    				return $this->Authenticate($email, $newPassword);
    			}
    		}
    	}
    	$error = 'Your token is invalid or has expired, please request a new one.';
    }
    return array('error' => $error);
	}
    
	
	
	/**
	 * Set user's unsubscribe token given the user_id
	 *
	 * @param int $userId
	 * @return bool
	 */
	function SetUnsubscribeToken($userId){
	
		$sql = "SELECT user_email, user_created FROM tbl_user WHERE user_deleted IS NULL AND user_id = :user_id";
		if($res = $this->DBobj->wrappedSql($sql, array(':user_id'=>$userId))){
			$usql = "UPDATE tbl_user SET user_unsubscribe = :unsub WHERE user_id = :id";
			$tok = sha1(md5(bin2hex(strrev(stripslashes(strtolower($res[0]['user_email']))))) . md5(stripslashes(strtoupper($res[0]['user_created']))));
			$this->DBobj->wrappedSql($usql, array("unsub"=>$tok,"id"=>$userId));
			return true;
		}
		return false;
	}
	
	
	/**
	 * Set user's unsubscribe token given the user's unsubscribe token and its created_datetime
	 * $createdDatetime string format '%Y%m%d%H%i%s'
	 *
	 * @param string $token
	 * @param string $createdDatetime
	 * @return bool
	 */
	function UnsubscribeUser($token, $createdDatetime, $isSMS = false){
	
		if(!empty($token) && !empty($createdDatetime)){
			$params = array("token"=>$token,"timeloc"=>$createdDatetime);
			$sql = "SELECT user_id, user_email FROM tbl_user WHERE user_unsubscribe = :token AND DATE_FORMAT(user_created,'%Y%m%d%H%i%s') = :timeloc";
			if($res = $this->DBobj->wrappedSql($sql,$params)){
				$promoStr = ($isSMS) ? "user_sms_promo = 0" : "user_email_promo = 0";
				$usql = "UPDATE tbl_user SET {$promoStr} WHERE user_id = :id";
				$this->DBobj->wrappedSql($usql,array("id"=>$res[0]['user_id']));
				return $res[0]['user_email'];
			}
		}
		return false;
	}
	
	
	/**
	 * Insert new address in tbl_address and returns address_id
	 * Require associative array: (address_user_id, address_name, address_telephone, address_mobile, address_line1, address_line2,
	 address_suburb, address_state, address_country, address_postcode)
	 * @param array $addressArr
	 * @return int
	 */
	function InsertNewAddress($addressArr) {
		 
		$params = array (
				":address_user_id" => $addressArr['address_user_id'],
				":address_name" => (empty($addressArr['address_name'])?'':$addressArr['address_name']),
				":address_surname" => (empty($addressArr['address_surname'])?'':$addressArr['address_surname']),
				":address_telephone" => (empty($addressArr['address_telephone'])?'':$addressArr['address_telephone']),
				":address_mobile" => (empty($addressArr['address_mobile'])?'':$addressArr['address_mobile']),
				":address_line1" => (empty($addressArr['address_line1'])?'':$addressArr['address_line1']),
				":address_line2" => (empty($addressArr['address_line2'])?'':$addressArr['address_line2']),
				":address_suburb" => (empty($addressArr['address_suburb'])?'':$addressArr['address_suburb']),
				":address_state" => (empty($addressArr['address_state'])?'':$addressArr['address_state']),
				":address_country" => (empty($addressArr['address_country'])?'':$addressArr['address_country']),
				":address_postcode" => (empty($addressArr['address_postcode'])?'':$addressArr['address_postcode'])
		);
		 
		$sql = "SELECT address_id FROM tbl_address WHERE
    					address_user_id = :address_user_id AND
    					address_name = :address_name AND
    	        address_surname = :address_surname AND
    					address_telephone = :address_telephone AND
    					address_mobile = :address_mobile AND
    					address_line1 = :address_line1 AND
    					address_line2 = :address_line2 AND
    					address_suburb = :address_suburb AND
    					address_state = :address_state AND
    					address_country = :address_country AND
    					address_postcode = :address_postcode AND
    					address_deleted IS NULL";
		 
		if($res = $this->DBobj->wrappedSql($sql, $params)){
			$sql = "UPDATE tbl_address SET address_modified = now()  WHERE address_id = :id ";
			$this->DBobj->wrappedSql($sql, array(':id'=>$res[0]['address_id']) );
			return $res[0]['address_id'];
		} else {
			$sql = " INSERT INTO tbl_address (
	        						address_user_id, address_name, address_surname, address_telephone, address_mobile, address_line1, address_line2,
									address_suburb, address_state, address_country, address_postcode,	address_created
									)
								VALUES (
									:address_user_id, :address_name, :address_surname, :address_telephone, :address_mobile, :address_line1, :address_line2,
									:address_suburb, :address_state, :address_country, :address_postcode,	now()
								)";
		  
			if($this->DBobj->wrappedSql($sql, $params)){
				return $this->DBobj->wrappedSqlIdentity();
			}
		}
		return 0;
	}
	
	
	
	/**
	 * Return array recordset given the address_id
	 *
	 * @param int $addressId
	 * @return array
	 */
	function GetAddress($addressId) {
	
		$sql = "SELECT * FROM tbl_address WHERE address_deleted IS NULL AND address_id = :id ";
		$res = $this->DBobj->wrappedSql($sql, array(':id' => $addressId) );
		return $res[0];
	}
	
	
	
	/**
	 * Return array recordset given the field address_user_id
	 *
	 * @param int $addressId
	 * @return array
	 */
	function GetUsersAddresses($addressUserId = 0, $orderby = '') {
		
		$addressUserId = empty($addressUserId) ? $this->user_id : $addressUserId;
		if(!empty($orderby)){
			$orderby = 'ORDER BY ' . $orderby;
		}else{
			$orderby = 'ORDER BY address_modified DESC';
		}
		$sql = "SELECT * FROM tbl_address WHERE address_deleted IS NULL AND address_user_id = :id " . $orderby;
		return $this->DBobj->wrappedSql($sql, array(':id' => $addressUserId));
	}
	
	
   
	/**
	 * Set user authentication cookie.
	 * $userId: default 0 (if null/zero or value does not match with any record then will unset the cookie).
	 * $name: default 'usrauth'
	 *
	 * @param string $name
	 * @param int $userId
	 * @return boolean
	 */
	function SetUserAuthCookie($name ='usrauth', $userId = 0) {
	
		// Get user unique-hash (user_password)
		$userStr = '';
		if(!empty($userId)){
			$sql = "SELECT user_password FROM tbl_user WHERE user_deleted IS NULL AND user_id = :id";
			if($res = $this->DBobj->wrappedSql($sql,array(":id"=>$userId))){
				$userStr = $res[0]['user_password']; // string (40 char)
			}
		}
	
		$value = '';
		$expTime = time() - (60*60*24*14); // past 14 days
		if(!empty($userStr)){
			$browser = md5($_SERVER['HTTP_USER_AGENT']); // string (32 char)
			$expTime = time()+(60*60*24*14); // next 14 days
			$expTimeStr = dechex($expTime); // string (+8 char)
	
	
			// Build cookie string value
			$value = $userStr . $browser . $expTimeStr;
		}
	
		//SET COOKIE
		$_SECURE_COOKIE = false;
		if($_SERVER['SERVER_PORT'] == 443 || !empty($_SERVER['HTTPS'])){
			$_SECURE_COOKIE = true; /*IF HTTPs TURN THIS ON */
		}
	
		$currentCookieParams = session_get_cookie_params();
	
		setcookie($name,//name
		$value,//value
		$expTime,//expires at end of session
		$currentCookieParams['path'],//path
		$currentCookieParams['domain'],//domain
		$_SECURE_COOKIE, //secure
		true  //httponly: Only accessible via http. Not accessible to javascript
		);
	
		if(empty($value)){
			unset($_COOKIE[$name]);
			return false;
		}
		return true;
	}
	
	
	/**
	 * Check user authentication cookie.
	 * $name: default 'usrauth'
	 *
	 * @param string $name
	 * @return array
	 */
	function checkUserAuthCookie($name ='usrauth') {
	
		if(!empty($_COOKIE[$name]) && strlen($_COOKIE[$name]) > 79 && strlen($_COOKIE[$name]) < 82 ){
	
			// Get variables
			$userStr = substr($_COOKIE[$name], 0, 40);
			$browser = substr($_COOKIE[$name], 40, 32);
			$expTime = hexdec(substr($_COOKIE[$name], 72));
	
			// Validate expiration date
			if($expTime > time() && $expTime <= (time()+(60*60*24*14)) ){
				// validate browser
				if($browser == md5($_SERVER['HTTP_USER_AGENT'])){
					// Validate user hash (user_password)
					$sql = "SELECT * FROM tbl_user WHERE user_deleted IS NULL AND user_password = :password";
					if($res = $this->DBobj->wrappedSql($sql, array("password" => $userStr))){
						return array(
								"id"=>$res[0]["user_id"],
								"gname"=>$res[0]["user_gname"],
								"surname"=>$res[0]["user_surname"],
								"email"=>$res[0]["user_email"],
								"loggedInByCookie"=>true,
								"extra" => ($this->GetExtraUserFields())
						);
					}
				}
			}
		}
		// Clear user authentication cookie
		$this->SetUserAuthCookie($name);
		return false;
	}
	
	
	/**
	 * Check user authentication cookie.
	 * $name: default 'usrauth'
	 *
	 * @param string $name
	 * @return array
	 */
	function LoginFromExternalSite($_token) {
	
		// Get variables
		$userStr = substr($_token, 0, 40);
		$browser = substr($_token, 40, 32);
		$expTime = hexdec(substr($_token, 72));
		// Validate expiration date
		if($expTime > time() && $expTime <= (time()+(60*5)) ){
			// validate browser
			if($browser == md5($_SERVER['HTTP_USER_AGENT'])){
				// Validate user hash (user_password)
				$sql = "SELECT * FROM tbl_user WHERE user_deleted IS NULL AND user_password = :password";
				if($res = $this->DBobj->wrappedSql($sql, array("password" => $userStr))){
					return array(
							"id"=>$res[0]["user_id"],
							"gname"=>$res[0]["user_gname"],
							"surname"=>$res[0]["user_surname"],
							"email"=>$res[0]["user_email"],
							"loggedInByCookie"=>true,
							"extra" => ($this->GetExtraUserFields())
					);
				}
			}
		}
		return false;
	}
	
	
	/**
	 * Get extra/additional user's field - CHANGE THIS FUNCTION TO GET OTHER FIELDS FROM DIFFERENT TABLES  
	 * 
	 * @param int $_userId
	 * @return array
	 */
	function GetExtraUserFields($_userId = 0){
		$result = array();
		
		$userId = empty($_userId) ? $this->user_id : $_userId; 
		$sql = "SELECT user_mobile, user_group, user_email_promo, user_sms_promo FROM tbl_user WHERE user_id = :id AND user_deleted IS NULL";
		$res = $this->DBobj->wrappedSql($sql, array(":id" => $userId));
		$result[] = $res[0]; 
		
		return $result;
	}
	
	
	/** >>> WARNING: THIS FUNCTION HASN'T BEEN UPDATED 
	 * 
	 * Authenticate the user using Facebook account.
	 * First time, Insert a new record in tbl_user with additional Facebook info and return associative array: ('id', 'gname', 'surname', 'email', 'social_name', 'social_id')
	 * On existing, just return associative array: ('id', 'gname', 'surname', 'email', 'social_name', 'social_id')
	 * On error return associative array: ('error')
	 * Require associative array: ('id', 'first_name', 'last_name', 'email', additional fields )
	 *
	 * @param array $user
	 * @return array
	 */
	function AuthenticateFacebook($user){
		 
		if($user['id']) {
			if($res = $this->RetrieveByUsername($user['id'])) { // ALREADY REGISTERED
				$user_arr["id"]=$res["user_id"];
				$user_arr["gname"]=$res["user_gname"];
				$user_arr["surname"]=$res["user_surname"];
				$user_arr["email"]=$res["user_email"];
				$user_arr["social_name"]=$res["user_social_name"];
				$user_arr["social_id"]=$res["user_social_id"];
	
			} else {			//REGISTER WITH FACEBOOK DETAILS
				$params = array (
						":gname" => $user['first_name'],
						":surname" => $user['last_name'],
						":email" => $user['email'],
						":social_name" => 'facebook',
						":social_id" => $user['id'],
						":social_info" => json_encode($user),
						":ip" => $_SERVER['REMOTE_ADDR'],
						":browser" => $_SERVER['HTTP_USER_AGENT']
				);
				 
				$sql = "INSERT INTO tbl_user (user_username, user_gname, user_surname, user_email, user_social_name, user_social_id, user_social_info, user_ip, user_browser, user_created)
									values(:social_id, :gname, :surname, :email, :social_name, :social_id, :social_info, :ip, :browser, now() )";
				if($this->DBobj->wrappedSql($sql, $params)){
					$userId =  $this->DBobj->wrappedSqlIdentity();
					$user_arr = array (
							"id" => $userId,
							"gname" => $user['first_name'],
							"surname" => $user['last_name'],
							"email" => $user['email'],
							"social_name" => 'facebook',
							"social_id" => $user['id']
					);
				}
			}
			return $user_arr;
		}
	
		return array( "error" => 'Missing Facebook ID.');
	}
    
}
	

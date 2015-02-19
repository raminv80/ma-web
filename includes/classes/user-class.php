<?php


class UserClass {

	
    /**
     * Insert a new record in tbl_user and return associative array: ('id', 'gname', 'surname', 'email') 
     * On error return associative array: ('error')
     * Require associative array: ('gname', 'surname', 'password', 'email')
     *  
     * @param array $user
     * @return array
     */
    function Create($user, $overWriteExisting = false){
    	global $DBobject, $SITE;
    
    	if ($res = $this->RetrieveByUsername($user['username'])) {
    		if($overWriteExisting){
    			if($res['user_gname'] != $user['gname']){
    				$sql = "UPDATE tbl_user SET user_gname = :gname, user_want_promo = :promo, user_modified = now() WHERE user_id = :id ";
    				$DBobject->wrappedSql($sql, array(':id'=>$res['user_id'],':gname'=>$user['gname'],':promo'=>$user['wantpromo']));
    			}
    			return $result = array (
    					"id" => $res['user_id'],
    					"gname" => $user['gname'],
    					"surname" => $user['surname'],
    					"email" => $res['user_email']
    			);
    		}else{
    			return array ('error' => "This email '{$user['username']}' has already been used.");
    		}
    	} else {
    		
    	
	    	$temp_str = getPass($user['email'],$user['password']);
	    	
	    	$params = array (
	    			":username" => $user['username'],
	    			":gname" => $user['gname'],
	    			":surname" => $user['surname'],
	    			":email" => $user['email'],
	    			":password" => $temp_str,
	    			":user_site" => $SITE,
    				":want_promo" => $user['want_promo'],
	        	":ip" => $_SERVER['REMOTE_ADDR'],
	        	":browser" => $_SERVER['HTTP_USER_AGENT']
	    	);
	    	
	    	$sql = "INSERT INTO tbl_user (
										user_username,
	    							user_gname,
										user_surname,
										user_email,
										user_password,
					    			user_site,
	    							user_want_promo,
										user_ip,
										user_browser,
										user_created
									)
									values(
	    							:username,
										:gname,
										:surname,
										:email,
										:password,
					    			:user_site,
	    							:want_promo,
	    							:ip,
	    							:browser,
										now()
										)";
	    	if ( $DBobject->wrappedSql($sql, $params) ) {
	    		$userId =  $DBobject->wrappedSqlIdentity();
	    		$result = array (
	    				"id" => $userId,
	    				"gname" => $user['gname'],
	    				"surname" => $user['surname'],
	    				"email" => $user['email']
	    		);
	    	} else {
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
    	global $DBobject;
        
        $res = $this->Authenticate($data['email'], $data['old_password']);
        
    	if ($res['error']) {
    		return array ('error' => 'Incorrect old password.');
    		
    	} else {
	    	$temp_str = getPass($data['email'],$data['password']);
	    	
	    	$params = array (
	    			":id" => $res['id'],
	    			":password" => $temp_str,
	        		":ip" => $_SERVER['REMOTE_ADDR'],
	        		":browser" => $_SERVER['HTTP_USER_AGENT']
	    	);
	    	$sql = "UPDATE tbl_user SET user_password = :password, user_ip = :ip, user_browser = :browser, user_modified = now()
				WHERE user_id = :id ";
	    	
	    	if ( $DBobject->wrappedSql($sql, $params) ) {
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
    	global $DBobject;
    
    	$sql = "SELECT * FROM tbl_user
				WHERE user_username = :uname AND user_deleted IS NULL";
    	 
    	$res  = $DBobject->wrappedSql($sql, array( ":uname" => $uname ));
    
    	return $res[0];
    }
    
    
    /**
     * Get a single non-deleted record from tbl_user given the user id
     * On error return false
     * Require int: user_id
     * 
     * @param int $id
     * @return mixed
     */
    function RetrieveById($id){
    	global $DBobject;
    
    	$sql = "SELECT user_id, user_gname, user_surname, user_email FROM tbl_user
				WHERE user_id = :id AND user_deleted IS NULL";
    
    	$res  = $DBobject->wrappedSql($sql, array( ":id" => $id ));
    
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
    	global $DBobject;
    
    	$user_arr = array();
    	$temp_str = getPass($email,$pass);
    	
    	$sql = "SELECT * FROM tbl_user WHERE user_username = :email AND user_password = :password AND user_deleted IS NULL";
    	$params = array( 
    				"email" => $email , 
    				"password" => $temp_str 
    	);
    	
    	if( $res = $DBobject->wrappedSql($sql , $params) ){
    		$user_arr["id"]=$res[0]["user_id"];
    		$user_arr["gname"]=$res[0]["user_gname"];
    		$user_arr["surname"]=$res[0]["user_surname"];
    		$user_arr["email"]=$res[0]["user_email"];
    	} else {
    		$user_arr["error"] = "Wrong email or password";
    	}
    	return $user_arr;
    }
    
  
	/**
	 * NOT DEFINED YET
	 */
    function UpdateDetails($data){
    	global $DBobject;
    	
    	if ($res = $this->RetrieveByUsername($data['email'])){
    	
    		$params = array (
    				":id" => $res['user_id'],
    				":gname" => $data['gname'],
	    			":surname" => $data['surname'],
    				":want_promo" => $data['want_promo'],
    				":ip" => $_SERVER['REMOTE_ADDR'],
    				":browser" => $_SERVER['HTTP_USER_AGENT']
    		);
    		$sql = "UPDATE tbl_user SET user_gname = :gname, user_surname = :surname, user_ip = :ip, user_want_promo = :want_promo, user_browser = :browser, user_modified = now()
                            WHERE user_id = :id ";
    	
    		if ( $DBobject->wrappedSql($sql, $params) ) {
    			return array ('success' => 'Your details have been updated.');
    		}
    		return array ('error' => 'There was a connection problem. Please, try again!');
    	}
    	return array( 'error' => "This email '{$data['email']}' does not exist in our database.");
    }
 
    
    /**
     * Update the password field given the email in tbl_user and return associative array: ('success') 
     * On error return associative array: ('error')
     * Require strings:  email and password
     * 
     * @param string $email
     * @return array
     */
    function ResetPassword($email){
    	global $DBobject,$SMARTY;
    
        if ($res = $this->RetrieveByUsername($email)){
            $newPass = genRandomString(10);
            
            $temp_str = getPass($email,$newPass);
            
            
            $params = array (
                            ":id" => $res['user_id'],
                            ":password" => $temp_str,
                            ":ip" => $_SERVER['REMOTE_ADDR'],
                            ":browser" => $_SERVER['HTTP_USER_AGENT']
            );
            $sql = "UPDATE tbl_user SET user_password = :password, user_ip = :ip, user_browser = :browser, user_modified = now()
                   WHERE user_id = :id ";

            if ( $DBobject->wrappedSql($sql, $params) ) {
            	return array ('success' => 'Your new password has been sent to the registered email address.', 
            							'temp_pass' =>$newPass,
            							'user_gname' =>$res['user_gname']);
            } 
            return array ('error' => 'There was a connection problem. Please, try again!');
        }
    	return array( 'error' => "Sorry, '{$email}' does not appear to be registered with this site. Can you please check your email address or please create an account.");
    }
    
    /**
     * Insert new address in tbl_address and returns address_id
     * Require associative array: (address_user_id, address_name, address_telephone, address_mobile, address_line1, address_line2,
								address_suburb, address_state, address_country, address_postcode)
     * @param array $addressArr
     * @return int
     */
    function InsertNewAddress($addressArr) {
    	global $DBobject;
    	
    	$params = array (
    			":address_user_id" => $addressArr['address_user_id'],
    			":address_name" => $addressArr['address_name'],
    			":address_telephone" => $addressArr['address_telephone'],
    			":address_mobile" => $addressArr['address_mobile'],
    			":address_line1" => $addressArr['address_line1'],
    			":address_line2" => $addressArr['address_line2'],
    			":address_suburb" => $addressArr['address_suburb'],
    			":address_state" => $addressArr['address_state'],
    			":address_country" => $addressArr['address_country'],
    			":address_postcode" => $addressArr['address_postcode']
    	);
    	
    	$sql = "SELECT address_id FROM tbl_address WHERE 
    					address_user_id = :address_user_id AND 
    					address_name = :address_name AND  
    					address_telephone = :address_telephone AND  
    					address_mobile = :address_mobile AND  
    					address_line1 = :address_line1 AND  
    					address_line2 = :address_line2 AND  
    					address_suburb = :address_suburb AND  
    					address_state = :address_state AND  
    					address_country = :address_country AND  
    					address_postcode = :address_postcode AND
    					address_deleted IS NULL";
    	
    	if ( $res = $DBobject->wrappedSql ( $sql, $params ) ) {
    		return $res[0]['address_id'];
    	} else {
	    	$sql = " INSERT INTO tbl_address (
	        						address_user_id, address_name, address_telephone, address_mobile, address_line1, address_line2,
									address_suburb, address_state, address_country, address_postcode,	address_created
									)
								VALUES (
									:address_user_id, :address_name, :address_telephone, :address_mobile, :address_line1, :address_line2,
									:address_suburb, :address_state, :address_country, :address_postcode,	now()
								)";
	    
	    	if ( $DBobject->wrappedSql ( $sql, $params ) ) {
	    		return $DBobject->wrappedSqlIdentity();
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
    	global $DBobject;
    
    	$sql = "SELECT * FROM tbl_address WHERE address_id = :id ";
    	$res = $DBobject->wrappedSql ( $sql, array(':id' => $addressId) );
    	return $res[0];
    }
    
    /**
     * Return array recordset given the field address_user_id
     * 
     * @param int $addressId
     * @return array
     */
    function GetUsersAddresses($addressUserId, $orderby = '') {
    	global $DBobject;

    	if (!empty($orderby)){
    		$orderby = 'ORDER BY ' . $orderby;
    	}else{
    		$orderby = 'ORDER BY address_modified DESC';
    	}
    	$sql = "SELECT * FROM tbl_address WHERE address_user_id = :id " . $orderby;
    	return $DBobject->wrappedSql($sql, array(':id' => $addressUserId));
    }
    
    
    /**
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
    	global $DBobject;
    	
		if ($user['id']) {
			if ($res = $this->RetrieveByUsername($user['id'])) { // ALREADY REGISTERED
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
    			
    			$sql = "INSERT INTO tbl_user (
										user_username,
										user_gname,
										user_surname,
										user_email,
										user_social_name,
										user_social_id,
										user_social_info,
										user_ip,
										user_browser,
										user_created
									)
									values(
										:social_id,
										:gname,
										:surname,
										:email,
				    					:social_name,
				    					:social_id,
				    					:social_info,
	    								:ip,
	    								:browser,
										now()
										)";
    			if ( $DBobject->wrappedSql($sql, $params) ) {
    				$userId =  $DBobject->wrappedSqlIdentity();
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
    
    
    function AuthenticateTwitter(){
    	global $DBobject;
    
   
    }
}
	

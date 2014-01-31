<?php


class UserClass {

	
    
    function Create($user){
    	global $DBobject;
    
    	if ($this->RetrieveByUsername($user)) {
    		return array ('error' => 'This email is already been used.');
    	} else {
    		
    	
	    	$temp_str = sha1(md5(bin2hex(strrev(stripslashes($user['email'])))) . md5(stripslashes(strtoupper($user['password']))));
	    	
	    	$params = array (
	    			":gname" => $user['gname'],
	    			":surname" => $user['surname'],
	    			":email" => $user['email'],
	    			":password" => $temp_str,
	        		":ip" => $_SERVER['REMOTE_ADDR'],
	        		":browser" => $_SERVER['HTTP_USER_AGENT']
	    	);
	    	
	    	$sql = "INSERT INTO tbl_user (
										user_username,
	    								user_gname,
										user_surname,
										user_email,
										user_password,
										user_ip,
										user_browser,
										user_created
									)
									values(
	    								:email,
										:gname,
										:surname,
										:email,
										:password,
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
	    				"email" => $user['email'],
	    		);
	    	} else {
	    		$result = array ('error' => 'There was a connection problem. Please, try again!');
	    	}
	    	return  $result;
    	}
    	
    }
    
    
    function RetrieveByUsername($uname){
    	global $DBobject;
    
    	$sql = "SELECT user_id, user_gname, user_surname, user_email FROM tbl_user
				WHERE user_username = :uname AND user_deleted IS NULL";
    	 
    	$res  = $DBobject->wrappedSql($sql, array( ":uname" => $uname ));
    
    	return $res[0];
    }
    
    function RetrieveById($id){
    	global $DBobject;
    
    	$sql = "SELECT user_id, user_gname, user_surname, user_email FROM tbl_user
				WHERE user_id = :id AND user_deleted IS NULL";
    
    	$res  = $DBobject->wrappedSql($sql, array( ":id" => $id ));
    
    	return $res[0];
    }
    
    
    function Authenticate($email, $pass){
    	global $DBobject;
    
    	$user_arr = array();
    	$temp_str = sha1(md5(bin2hex(strrev(stripslashes($email)))) . md5(stripslashes(strtoupper($pass))));
    	
    	$sql = "SELECT * FROM tbl_user WHERE user_email = :email AND user_password = :password";
    	$params = array( 
    				"email" => $email , 
    				"password" => $temp_str 
    	);
    	
    	if( $res = $DBobject->wrappedSql($sql , $params) ){
    		$user_arr["id"]=$res[0]["user_id"];
    		$user_arr["gname"]=$res[0]["user_gname"];
    		$user_arr["surname"]=$res[0]["user_surname"];
    		$user_arr["email"]=$res[0]["user_email"];
    		//SaveAdminLogIn($row['admin_id']);		//<<<<<<<<<======= Login log????
    	} else {
    		$user_arr["error"] = "Wrong email or password";
    	}
    	return $user_arr;
    }
  

    function Update(){
    	global $DBobject;
    
    	/*   	$params = array (
    	 ":gname" => $user['gname'],
    			":surname" => $user['surname'],
    			":password" => $user['password'],
    			":ip" => $_SERVER['REMOTE_ADDR'],
    			":browser" => $_SERVER['HTTP_USER_AGENT']
    	);
    	 
    	$sql = "UPDATE tbl_user
    	SET user_gname = :gname,
    	user_surname = :surname,
    	user_password = :password,
    	user_ip = :ip,
    	user_browser = :browser,
    	user_modified = now()
    	WHERE user_email = :email";
    	 
    	$res  = $DBobject->wrappedSql($sql, $params);
    
    	return false; */
    }
    
    
    function ResetPassword(){
    	global $DBobject;
    
    
    	return false;
    }
    
    
    function AuthenticateFacebook($user){
    	global $DBobject;
    	
		if ($user['id']) {
    
			if ($this->RetrieveByUsername($user['id'])) { // ALREADY REGISTERED
    			$user_arr["id"]=$res[0]["user_id"];
    			$user_arr["gname"]=$res[0]["user_gname"];
    			$user_arr["surname"]=$res[0]["user_surname"];
    			$user_arr["email"]=$res[0]["user_email"];
    			$user_arr["social_name"]=$res[0]["user_social_name"];
    			$user_arr["social_id"]=$res[0]["user_social_id"];
    			//SaveAdminLogIn($row['admin_id']);		//<<<<<<<<<======= Login log????
    		
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
    						"gname" => $user['gname'],
    						"surname" => $user['surname'],
    						"email" => $user['email'],
    				);
    			}
    		}
    		return $user_arr;
		}
    
    	return array( "error" => 'Missing Facebook ID.');
    }
    
    
    function AuthenticateTwitter(){
    	global $DBobject;
    
    
    	return false;
    }
}
	

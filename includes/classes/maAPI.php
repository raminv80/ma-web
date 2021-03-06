<?php

//
// -----------------------------------------------------------
// REQUIRED FILES
//


//
// -----------------------------------------------------------
// EXCEPTIONS
//

// Defaine the base exception class (as a catch all for the API)
class exceptionMedicAlertApi extends Exception{}

// And the individual errors that can occur
class exceptionMedicAlertNotFound extends exceptionMedicAlertApi{}
class exceptionMedicAlertApiNotAuthenticated extends exceptionMedicAlertApi{}
class exceptionMedicAlertApiSessionExpired extends exceptionMedicAlertApi{}
class exceptionMedicAlertTransferTimeout extends exceptionMedicAlertApi{}
class exceptionMedicAlertInternalServerError extends exceptionMedicAlertApi{}
class exceptionMedicAlertLocked extends exceptionMedicAlertApi{}
class exceptionMedicAlertPasswordMismatch extends exceptionMedicAlertApi{}

//
// -----------------------------------------------------------
// DEFINITION
//
class medicAlertApi {
	// Define the member status types
	const STATUS_ACTIVE = 1;
	const STATUS_UNFINANCIAL = 2;
	const STATUS_DECEASED = 3;
	const STATUS_UNCLAIMED = 4;
	const STATUS_NO_LONGER_REQUIRED = 5;
	const STATUS_ACTIVE_PENDING = 6;

	// Define the membership types
	const MEMBERSHIP_GRANDFATHER = 1;
	const MEMBERSHIP_CLICKOVER = 2;
	const MEMBERSHIP_ANNUAL = 3;
	const MEMBERSHIP_BUSINESS = 4;
	const MEMBERSHIP_LIFETIME = 5;
	const MEMBERSHIP_BENOVOLENT = 6;
	const MEMBERSHIP_BENOVOLENT_ANNUAL = 7;

	// Define the Conditon/Medication/Allergy states
	const CMA_EXISTING = 1;
	const CMA_NEW = 2;
	const CMA_UPDATED = 3;
	const CMA_DELETED = 4;

	// Define the join category
	const JOIN_CATEGORY_TRADITIONAL_STAINLESS_STEEL = 1;
	const JOIN_CATEGORY_CLASSIC_STERLING_SILVER = 2;
	const JOIN_CATEGORY_ELEGANCE_GOLD_FILLED = 3;
	const JOIN_CATEGORY_ELITE_9CT_SOLID_GOLD = 4;
	const JOIN_CATEGORY_ELITE_18CT_SOLID_GOLD = 5;
	const JOIN_CATEGORY_OTHER = 6;

	// Define order types
	const ORDER_TYPE_EXISTING_MEMBER = 20;
	const ORDER_TYPE_NEW_MEMBER = 10;

	private $SERVER; // Server URL
	
	protected $DBobj;
	
	function __construct($_db = '') {
	  global $DBobject;
	
	  $this->SERVER = getenv('MEMBERSHIP_API_ENDPOINT');
	  
	  $this->DBobj = empty($_db)?$DBobject:$_db;
	}
	
	/**
	 * Authenticate with the MedicAlert Database
	 *
	 * @param string $membershipNumber
	 * @param string $passWord
	 * @return string
	 *
	 * @throws exceptionMedicAlertNotFound
	 * @throws exceptionMedicAlertApiNotAuthenticated
	 * @throws exceptionMedicAlertTransferTimeout
	 */
	public function authenticate($membershipNumber, $passWord)
	{
		$params = array(
			'membershipNumber' => $membershipNumber,
			'passWord' => md5($passWord)
		);

		try{
			$response = $this->_processRequest($this->SERVER . '/authenticate.php', $params);
		}catch(Exception $e){
			throw $e;
		}

		return $response;
	}

	/**
	 * Logs the person out (Marks the token as invalid)
	 *
	 * @param string $sessionToken
	 * @return string
	 *
	 * @throws exceptionMedicAlertTransferTimeout
	 */
	public function logOut($sessionToken)
	{
		$params = array(
			'sessionToken' => $this->_saltToken($sessionToken, MD5($this->_getRequestIp()))
		);

		try{
			$response = $this->_processRequest($this->SERVER . '/logout.php', $params);
		}catch(Exception $e){
			$sql = "INSERT INTO tbl_error (error_description, error_trace, error_ip) VALUES ('MedicAlert Members system Error (logOut)','".clean("".$e)."','{$_SERVER['REMOTE_ADDR']}')";
			$this->DBobj->wrappedSql($sql);
			throw $e;
		}
		return $response;
	}

	/**
	 * Sent out a lost passWord email for the specified membership number (email address must match)
	 *
	 * @param string $sessionToken
	 * @param string $membershipNumber
	 * @param string $emailAddress
	 * @return string
	 */
	public function lostPassWord($sessionToken, $membershipNumber, $emailAddress)
	{
		$params = array(
			'sessionToken' => $this->_saltToken($sessionToken, MD5($this->_getRequestIp())),
			'membershipNumber' => $membershipNumber,
			'emailAddress' => $emailAddress
		);

		try{
			$response = $this->_processRequest($this->SERVER . '/lost_password.php', $params);
		}catch(Exception $e){
			throw $e;
		}

		return $response;
	}

	/**
	 * Update the members password
	 *
	 * @param string $sessionToken
	 * @param string $existingPassWord
	 * @param string $newPassWord
	 * @return string
	 */
	public function updatePassWord($sessionToken, $existingPassWord, $newPassWord)
	{
		$params = array(
			'sessionToken' => $this->_saltToken($sessionToken, MD5($this->_getRequestIp())),
			'existingPassWord' => md5($existingPassWord),
			'newPassWord' => md5($newPassWord)
		);

		try{
			$response = $this->_processRequest($this->SERVER . '/update_password.php', $params);
		}catch(Exception $e){
			throw $e;
		}

		return $response;
	}

	/**
	 * Create a new member record in the MedicAlert Database
	 *
	 * @param type $sessionToken
	 * @param type $memberRecord
	 * @return string
	 */
	public function memberCreate($sessionToken, $memberCreateRecord)
	{
		$params = array(
			'sessionToken' => $this->_saltToken($sessionToken, MD5($this->_getRequestIp())),
			'memberCreateRecord' => $memberCreateRecord
		);

		try{
			$response = $this->_processRequest($this->SERVER . '/member_create.php', $params);
		}catch(Exception $e){
			$sql = "INSERT INTO tbl_error (error_description, error_trace, error_ip) VALUES ('MedicAlert Members system Error (memberCreate)','".clean("".$e)."','{$_SERVER['REMOTE_ADDR']}')";
			$this->DBobj->wrappedSql($sql);
			throw $e;
		}
		
		if(empty($response)){
		 $body = "DATETIME: ".date("dd/mm/yy HH:i:s");
		  $body.= "TIME: ".time();
		  $body.= "URL: ".$this->SERVER . '/member_create.php'."<br/>";
		  $body.= "PARAMS: ".print_r($params, true)."<br/>";
		  $body.= "RESPONSE: ".print_r($response,true)."<br/>";
		  sendMail(getenv('EMAIL_APP_SUPPORT'), 'MedicAlert Members system', 'noreply@medicalert.org.au',
              'MedicAlert Members system no Member No', $body);
		}

		return $response;
	}

	/**
	 * Retrieve the current membership record for the specified session token.
	 *
	 * @param string $sessionToken
	 * @return string
	 *
	 * @throws exceptionMedicAlertNotFound
	 * @throws exceptionMedicAlertApiNotAuthenticated
	 * @throws exceptionMedicAlertTransferTimeout
	 */
	public function memberRetrieve($sessionToken)
	{
		/*assert(is_string($sessionToken));
		assert(strlen($sessionToken) > 0);*/

		$params = array(
			'sessionToken' => $this->_saltToken($sessionToken, MD5($this->_getRequestIp()))
		);

		try{
			$response = $this->_processRequest($this->SERVER . '/member_retrieve.php', $params);
		}catch(Exception $e){
			$sql = "INSERT INTO tbl_error (error_description, error_trace, error_ip) VALUES ('MedicAlert Members system Error (retrieveMember)','".clean("".$e)."','{$_SERVER['REMOTE_ADDR']}')";
			$this->DBobj->wrappedSql($sql);
			throw $e;
		}
		
		return $response;
	}

	/**
	 * Update the member record
	 *
	 * @param string $sessionToken
	 * @param string $memberRecord
	 * @return string
	 */
	public function memberUpdate($sessionToken, $memberRecord)
	{
		// if the full member record is passed in, then only return the website record
		$memberRecordArray = json_decode($memberRecord, TRUE);
		if( isset($memberRecordArray['webSiteRecord']) )
		{
			$memberRecord = json_encode($memberRecordArray['webSiteRecord']);
		}

		$params = array(
			'sessionToken' => $this->_saltToken($sessionToken, MD5($this->_getRequestIp())),
			'memberRecord' => $this->_escapeNewLines($memberRecord)
		);

		try{
			$response = $this->_processRequest($this->SERVER . '/member_update.php', $params);
		}catch(Exception $e){
			$sql = "INSERT INTO tbl_error (error_description, error_trace, error_ip) VALUES ('MedicAlert Members system Error (memberUpdate)','".clean("".$e)."','{$_SERVER['REMOTE_ADDR']}')";
			$this->DBobj->wrappedSql($sql);
			//mail("cmsemails@them.com.au", "MedicAlert Members system Error", "An error occured");
			throw $e;
		}

		return $response;
	}
	
	/**
	 * Process payment record
	 *
	 * @param string $sessionToken
	 * @param string $memberPaymentRecord
	 * @return string
	 */
	public function memberProcessPayment($sessionToken, $memberPaymentRecord)
	{
	  //create params array to pass to API
	  $params = array(
	      'sessionToken' => $this->_saltToken($sessionToken, MD5($this->_getRequestIp())),
	      'membershipNumber' => $this->_escapeNewLines($memberPaymentRecord['membershipNumber']),
	      'membershipYear' => $this->_escapeNewLines($memberPaymentRecord['membershipYear']),
	      'memberPaymentAmount' => $this->_escapeNewLines($memberPaymentRecord['memberPaymentAmount']),
	      'memberPaymentReference' => $this->_escapeNewLines($memberPaymentRecord['memberPaymentReference']),
	      'memberPaymentCardType' => $this->_escapeNewLines($memberPaymentRecord['memberPaymentCardType'])
	  );
	  
	  try{
	    $response = $this->_processRequest($this->SERVER . '/process_renewal_payment.php', $params);
	  }catch(Exception $e){
	    $sql = "INSERT INTO tbl_error (error_description, error_trace, error_ip) VALUES ('MedicAlert Members system Error (memberProcessPayment)','".clean("".$e)."','{$_SERVER['REMOTE_ADDR']}')";
	    $this->DBobj->wrappedSql($sql);
	    throw $e;
	  }
	  
	  return $response;
	}
	
	/**
	 * Process website order in membership system
	 *
	 * @param string $sessionToken
	 * @param string $memberOrderRecord
	 * @return string
	 */
	public function memberProcessWebsiteOrder($sessionToken, $memberOrderRecord)
	{
	  //create params array to pass to API
	  //$this->_saltToken($sessionToken, MD5($this->_getRequestIp()))
	  $orderType = self::ORDER_TYPE_EXISTING_MEMBER;
	  if($memberOrderRecord['orderType'] == 'new'){
		  $orderType = self::ORDER_TYPE_NEW_MEMBER;
	  }
	  $params = array(
		  'sessionToken' => $this->_escapeNewLines($this->_saltToken($sessionToken, MD5($this->_getRequestIp()))),
		  'membershipNumber' => $this->_escapeNewLines($memberOrderRecord['membershipNumber']),
	      'websiteOrderId' => $this->_escapeNewLines($memberOrderRecord['websiteOrderId']),
	      'orderItems' => $this->_escapeNewLines($memberOrderRecord['orderItems']),
	      'ccDetails' => $this->_escapeNewLines($memberOrderRecord['ccDetails']),
	      'discountCode' => $this->_escapeNewLines($memberOrderRecord['discountCode']),
	      'otype' => $this->_escapeNewLines($orderType),
	      'postage_amount' => $this->_escapeNewLines($memberOrderRecord['postageAmount']),
	      'donation_amount' => $this->_escapeNewLines($memberOrderRecord['donationAmount'])
	  );

	  try{
	    $response = $this->_processRequest($this->SERVER . '/process_website_order.php', $params);
	  }catch(Exception $e){
	    $sql = "INSERT INTO tbl_error (error_description, error_trace, error_ip) VALUES ('MedicAlert Members system Error (memberProcessOrder)','".clean("".$e)."','{$_SERVER['REMOTE_ADDR']}')";
	    $this->DBobj->wrappedSql($sql);
	    throw $e;
	  }
	  
	  return $response;
	}

	/**
	 * Preforms a search against the database looking for matches using the specified fields. At least 2 must be specified
	 *
	 * @param string $sessionToken
	 * @param string $firstName
	 * @param string $surName
	 * @param string $dateOfBirth
	 * @param string $postCode
	 * @param string $emailAddress
	 * @return string
	 */
	public function memberProfileMatch($sessionToken, $firstName='', $surName='', $dateOfBirth='', $postCode='', $emailAddress='')
	{
		// at least 2 fields need to be set
		$count = 0;
		if( strlen($firstName) > 0 ) $count++;
		if( strlen($surName) > 0 ) $count++;
		if( strlen($dateOfBirth) > 0 ) $count++;
		if( strlen($postCode) > 0 ) $count++;
		if( strlen($emailAddress) > 0 ) $count++;
		if( $count < 2 )
		{
			throw exceptionMedicAlertNotFound('At least 2 fields need to be set');
		}

		$params = array(
			'sessionToken' => $this->_saltToken($sessionToken, MD5($this->_getRequestIp())),
			'firstName' => $firstName,
			'surName' => $surName,
			'dateOfBirth' => $dateOfBirth,
			'postCode' => $postCode,
			'emailAddress' => $emailAddress,
		);

		try{
			$response = $this->_processRequest($this->SERVER . '/member_profile_match.php', $params);
		}catch(Exception $e){
			$sql = "INSERT INTO tbl_error (error_description, error_trace, error_ip) VALUES ('MedicAlert Members system Error (memberProfileMatch)','".clean("".$e)."','{$_SERVER['REMOTE_ADDR']}')";
			$this->DBobj->wrappedSql($sql);
			//mail("cmsemails@them.com.au", "MedicAlert Members system Error", "An error occured");
			throw $e;
		}

		return $response;
	}

	/**
	 *
	 * @param string $sessionToken
	 * @param string $fileId
	 * @return string
	 */
	public function fileRetrieve($sessionToken, $fileId)
	{
		$params = array(
			'sessionToken' => $this->_saltToken($sessionToken, MD5($this->_getRequestIp())),
			'fileId' => $fileId
		);

		try{
			$response = $this->_processRequest($this->SERVER . '/file_retrieve.php', $params);
		}catch(Exception $e){
			$sql = "INSERT INTO tbl_error (error_description, error_trace, error_ip) VALUES ('MedicAlert Members system Error (logOut)','".clean("".$e)."','{$_SERVER['REMOTE_ADDR']}')";
			$this->DBobj->wrappedSql($sql);
			//mail("cmsemails@them.com.au", "MedicAlert Members system Error", "An error occured");
			throw $e;
		}

		return $response;
	}
	
	/**
	 * Get member basic details
	 * Expected output: Object(
	 *   member_membership_number
	 *   status_id
     *   member_details_title
     *   member_details_firstname
     *   member_details_middlenames
     *   member_details_surname
     *   member_details_dob
     *   member_details_email
     *   member_address_postcode
	 * )
	 * @param string $sessionToken
	 * @param string $membershipNumber
	 * @return object
	 */
	public function getMemberBasicDetails($sessionToken, $membershipNumber)
	{
	
	  $params = array(
	      'sessionToken' => $this->_saltToken($sessionToken, MD5($this->_getRequestIp())),
	      'membershipNumber' => $membershipNumber
	  );
	
	  try{
	    $response = $this->_processRequest($this->SERVER . '/member_basic_details.php', $params);
	  }catch(Exception $e){
	    $sql = "INSERT INTO tbl_error (error_description, error_trace, error_ip) VALUES ('MedicAlert Members system Error (getMemberBasicDetails)','".clean("".$e)."','{$_SERVER['REMOTE_ADDR']}')";
	    $this->DBobj->wrappedSql($sql);
	    throw $e;
	  }
	
	  return $response;
	}
	
	/**
	 * Get members by birthday
	 * @param string $sessionToken
	 * @param int $day
	 * @param int $month
	 * @return object
	 */
	public function getMembersByBirthday($sessionToken, $day, $month)
	{
	
	  $params = array(
	      'sessionToken' => $this->_saltToken($sessionToken, MD5($this->_getRequestIp())),
	      'day' => $day,
	      'month' => $month
	  );
	
	  try{
	    $response = $this->_processRequest($this->SERVER . '/get_members_by_birthday.php', $params);
	  }catch(Exception $e){
	    $sql = "INSERT INTO tbl_error (error_description, error_trace, error_ip) VALUES ('MedicAlert Members system Error (getMembersByBirthday)','".clean("".$e)."','{$_SERVER['REMOTE_ADDR']}')";
	    $this->DBobj->wrappedSql($sql);
	    throw $e;
	  }
	
	  return $response;
	}

	/**
	 * Process the actual request
	 *
	 * @param string $url
	 * @param array $params
	 * @return string
	 *
	 * @throws exceptionMedicAlertApiNotAuthenticated
	 * @throws exceptionMedicAlertNotFound
	 * @throws exceptionMedicAlertApiSessionExpired
	 * @throws exceptionMedicAlertTransferTimeout
	 */
	private function _processRequest($url, $params)
	{
		// create the curl resource
		$ch = curl_init();

		// set url
		curl_setopt($ch, CURLOPT_URL, $url);

		// always include the IP of the request
		$params['remoteIp'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';

		// and the options
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,  $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		
		// $output contains the output string
		$response = curl_exec($ch);

		// get the response code
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		// close the connection
		curl_close($ch);

		if (200 == $httpCode && !empty($response)) {
			return $response;
		} else if( 401 == $httpCode ) {
			throw new exceptionMedicAlertApiNotAuthenticated();
		} else if( 404 == $httpCode ) {
			throw new exceptionMedicAlertNotFound();
		} else if( 407 == $httpCode ) {
			throw new exceptionMedicAlertApiSessionExpired();
		} else if( 408 == $httpCode ) {
			throw new exceptionMedicAlertTransferTimeout();
		} else if( 423 == $httpCode ) {
			throw new exceptionMedicAlertLocked();
		} else if( 500 == $httpCode ) {
			throw new exceptionMedicAlertInternalServerError();
		} else if( 603 == $httpCode ) {
			throw new exceptionMedicAlertPasswordMismatch('3 Attempts remaining');
		} else if( 602 == $httpCode ) {
			throw new exceptionMedicAlertPasswordMismatch('2 Attempts remaining');
		} else if( 601 == $httpCode ) {
			throw new exceptionMedicAlertPasswordMismatch('1 Attempt remaining');
		} else {
			throw new exceptionMedicAlertApi("Server unknown error - {$httpCode}");
		}
	}

	/**
	 * Returns the IP of the current request (default to localhost if not set)
	 *
	 * @return string
	 */
	private function _getRequestIp()
	{
		return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
	}

	/**
	* This fuction salts an unsalted token with some salt.
	*
	* @param string $unsalted_token (MD5)
	* @param string $salt			(MD5)
	* @return string
	*/
	private function _saltToken($unsalted_token, $salt)
	{
		// convert the MD5 strings into binary data
		$unsalted_token_bin = pack("H*", $unsalted_token);
		$salt_bin 			= pack("H*", $salt);

		// salt the token by XOR'ing it with the salt
		return bin2hex($unsalted_token_bin ^ $salt_bin);
	}

	/**
	 * This function escapes newline characters \n\r as curl strips them in the post
	 *
	 * @param string $data
	 */
	private function _escapeNewLines($data)
	{
		$data = str_replace("\\n", "\\\\n", $data);
		$data = str_replace("\\r", "\\\\r", $data);

		return $data;
	}
}

<?php
require_once 'maAPI.php';

class UserClass{
  var $medicAlertApi; // The MedicAlert API
  var $memberRecord = array(); // The member record array
  var $memberRecordJSONResponse; // The member record JSON response
  public $sessionVars = array(); // All the variables to be stored in session
  private $token = null;
  private $errorMsg = null;
  protected $DBobj = null; // Database object/connector - WEBSITE ONLY 


  function __construct(){
    global $DBobject;
    $this->DBobj = $DBobject;
    $this->medicAlertApi = new medicAlertApi();
  }


  /**
   * Authenticate with the MedicAlert Database
   *
   * @param string $_membershipId          
   * @param string $_password          
   * @return boolean
   */
  function authenticate($_membershipId, $_password){
    $this->errorMsg = null;
    try{
      $authJSONResponse = $this->medicAlertApi->authenticate($_membershipId, $_password);
      $authenticationRecord = json_decode($authJSONResponse, true);
      $this->token = $authenticationRecord['sessionToken'];
    }
    catch(exceptionMedicAlertApiNotAuthenticated $e){
      $this->errorMsg = "Invalid membership number or password.";
    }
    catch(exceptionMedicAlertPasswordMismatch $e){
      if($e->getMessage() == "1 Attempt remaining"){
        $this->errorMsg = "Sorry, your password was incorrect.<br>You have one attempt remaining.<br>Please try again carefully as your account will be locked after three attempts and you will have to call us during office hours to unlock your account.";
      } else if($e->getMessage() == "2 Attempts remaining"){
        $this->errorMsg = "Sorry, your password was incorrect.<br>You have two attempts remaining.<br>Please try again carefully as your account will be locked after three attempts and you will have to call us during office hours to unlock your account.";
      } else{
        $this->errorMsg = "Sorry, your password was incorrect.<br>Please try again carefully as your account will be locked after three attempts and you will have to call us during office hours to unlock your account.";
      }
    }
    catch(exceptionMedicAlertLocked $e){
      $this->errorMsg = "Sorry, you have been locked out of your account.<br>You will have to call us on 1800 88 22 22 (Mon-Fri, 9am-5pm CST) to unlock your account.";
    }
    
    if(empty($this->errorMsg) && $this->setSessionVars($this->token)){
      if($this->isInactiveMember()){
        // log out of the API
        $this->logOut($this->token);
      } else{
        // Authenticated and active
        return true;
      }
    }
    return false;
  }


  /**
   * Log out from MAF API
   * 
   * @param string $_token          
   * @return boolean
   */
  function logOut($_token = null){
    $token = empty($_token)? $this->token : $_token;
    try{
      $this->medicAlertApi->logout($token);
      $this->token = null;
      $this->sessionVars = array();
      $this->memberRecord = array();
      return true;
    }
    catch(exceptionMedicAlertApiNotAuthenticated $e){
      $this->errorMsg = "API error: {$e}";
    }
    catch(exceptionMedicAlertApiSessionExpired $e){
      $this->errorMsg = "API error: {$e}";
    }
    catch(exceptionMedicAlertApi $e){
      $this->errorMsg = "API error: {$e}";
    }
    return false;
  }


  /**
   * Initialise the member details array in the session based on the retrieved member record.
   * ALSO IT'S USED FOR CHECKING THE MEMBER LOGIN STATUS 
   * This session array is used to prefill the update personal/medical details form.
   */
  function setSessionVars($_token){
    $this->errorMsg = null;
    try{
      $this->memberRecordJSONResponse = $this->medicAlertApi->memberRetrieve($_token);
      $this->memberRecord = json_decode($this->memberRecordJSONResponse, true);
      //$this->memberRecord = stripslashes_deep($this->memberRecord);
    }
    catch(exceptionMedicAlertApiNotAuthenticated $e){
      $this->errorMsg = "API error: {$e}";
    }
    catch(exceptionMedicAlertApiSessionExpired $e){
      $this->errorMsg = "API error: {$e}";
    }
    catch(exceptionMedicAlertApi $e){
      $this->errorMsg = "API error: {$e}";
    }
    
    if(empty($this->errorMsg) && !empty($this->memberRecord)){
      $this->sessionVars['pending_update'] = false;
      
      $this->sessionVars['token'] = $_token;
      $this->sessionVars['main'] = $this->getUserArray($this->memberRecord);
      $this->sessionVars['pending'] = $this->buildMemberProfileArray($this->memberRecord['webSiteRecord']);
      $this->sessionVars['update'] = $this->buildMemberProfileArray($this->memberRecord['dataBaseRecord']);
      $this->sessionVars = stripslashes_deep($this->sessionVars);
      return true;
    }
    return false;
  }
	
	
	/**
	 * Get the website record for the member.
	 * Used on the view details form.
	 */
	function getWebsiteRecord(){
	
		try{
			$this->memberRecordJSONResponse = $this->medicAlertApi->memberRetrieve($this->token);
			$this->memberRecord = json_decode($this->memberRecordJSONResponse, true);
		}catch(exceptionMedicAlertApiNotAuthenticated $e){
			$_SESSION['user'] = null; unset($_SESSION['user']);
			header("Location: /Membership/Members_Area"); die();
		}catch(exceptionMedicAlertApiSessionExpired $e){
			$_SESSION['user'] = null; unset($_SESSION['user']);
			header("Location: /Membership/Members_Area"); die();
		}catch(exceptionMedicAlertApi $e){
			header("Location: /404"); die();
		}
	}
	
	
	/**
	 * Build new member array based on details entered
	 */
	private function buildNewMemberMedicAlertArray($_data){
	
		// Website record
		$this->memberRecord['initialPassword']						= $_data['password'];
		$this->memberRecord['joinCategory']							= medicAlertApi::JOIN_CATEGORY_OTHER;
		$this->memberRecord['memberJoinDate']						= date('d M Y');
		$this->memberRecord['accountBalance'] 						= '0.00';
		//$this->memberRecord['status']['statusTypeId']				= '6';
		$this->memberRecord['membership']['membershipTypeId']		= '3';
		//$this->memberRecord['membership']['RenewalDate']			= '';
	
		$this->memberRecord['details']['title']						= '';
		$this->memberRecord['details']['firstName']					= ucwords(strtolower($_data['gname']));
		$this->memberRecord['details']['middleName']				= '';
		$this->memberRecord['details']['surname']					= ucwords(strtolower($_data['surname']));
		$this->memberRecord['details']['nickName']					= '';
		$this->memberRecord['details']['dateOfBirth']				= $_data['db_dob'];
		$this->memberRecord['details']['gender']					= ucwords(strtolower($_data['gender']));
		$this->memberRecord['details']['phoneHome']					= '';
		$this->memberRecord['details']['phoneWork']					= '';
		$this->memberRecord['details']['phoneMobile']				= $_data['mobile'];
		$this->memberRecord['details']['emailAddress']				= $_data['email'];
		$this->memberRecord['details']['emergencyInformation']		= '';
		$this->memberRecord['details']['donorEye']					= '';
		$this->memberRecord['details']['donorHeart']				= '';
		$this->memberRecord['details']['donorKidney']				= '';
		$this->memberRecord['details']['donorFreeText']				= '';
		//$this->memberRecord['details']['receiveMarketingMaterial']	= '';
		$this->memberRecord['details']['correspondenceType']		= '';
		$this->memberRecord['details']['organDonor']				= '';
		$this->memberRecord['details']['howDidYouHear']				= '';
	
		$this->memberRecord['address']['address']					= ucwords(strtolower($_data['address']));
		$this->memberRecord['address']['suburb']					= ucwords(strtolower($_data['suburb']));
		$this->memberRecord['address']['postCode']					= $_data['postcode'];
		$this->memberRecord['address']['state']						= $_data['state'];
		$this->memberRecord['address']['country']					= 'Australia';
	
		$this->memberRecord['blood']['bloodGroup']					= '';
	
		$this->memberRecord['emergencyContact']['relationship']		= '';
		$this->memberRecord['emergencyContact']['name']				= '';
		$this->memberRecord['emergencyContact']['address']			= '';
		$this->memberRecord['emergencyContact']['suburb']			= '';
		$this->memberRecord['emergencyContact']['postCode']			= '';
		$this->memberRecord['emergencyContact']['state']			= '';
		$this->memberRecord['emergencyContact']['country']			= '';
		$this->memberRecord['emergencyContact']['phoneHome']		= '';
		$this->memberRecord['emergencyContact']['phoneWork']		= '';
		$this->memberRecord['emergencyContact']['phoneMobile']		= '';
	
		$this->memberRecord['doctor']['doctorName']					= '';
		$this->memberRecord['doctor']['medicalCentreName']			= '';
		$this->memberRecord['doctor']['fileNumber']					= '';
		$this->memberRecord['doctor']['address']					= '';
		$this->memberRecord['doctor']['suburb']						= '';
		$this->memberRecord['doctor']['postCode']					= '';
		$this->memberRecord['doctor']['state']						= '';
		$this->memberRecord['doctor']['country']					= '';
		$this->memberRecord['doctor']['phoneNumber']				= '';
		
		$this->memberRecord['conditions']							= '';
		$this->memberRecord['allergies']							= '';
		$this->memberRecord['medications']							= '';
	
		$this->memberRecord['attributes'][0]['id']					= '7';
		$this->memberRecord['attributes'][0]['text']				= 'Allergies Authenticated';
		$this->memberRecord['attributes'][0]['value']				= '';
		/*$this->memberRecord['attributes'][1]['id']					= '3';
		$this->memberRecord['attributes'][1]['text']				= 'Authenticated (application form)';
		$this->memberRecord['attributes'][1]['value']				= 'No';*/
		$this->memberRecord['attributes'][2]['id']					= '5';
		$this->memberRecord['attributes'][2]['text']				= 'Authenticated (MIR / FIR / AF)';
		$this->memberRecord['attributes'][2]['value']				= '';
		$this->memberRecord['attributes'][3]['id']					= '6';
		$this->memberRecord['attributes'][3]['text']				= 'Authenticated (MIR / FIR / AF) Date (year only)';
		$this->memberRecord['attributes'][3]['value']				= '';
		$this->memberRecord['attributes'][4]['id']					= '8';
		$this->memberRecord['attributes'][4]['text']				= 'How did you hear about MedicAlert?';
		$this->memberRecord['attributes'][4]['value']				= $_data['heardabout'];
		//NEW FIELDS 08/05/2014
		$this->memberRecord['attributes'][5]['id']					= '10';
		$this->memberRecord['attributes'][5]['text']				= 'DVA Gold Card Number';
		$this->memberRecord['attributes'][5]['value']				= '';
		$this->memberRecord['attributes'][6]['id']					= '12';
		$this->memberRecord['attributes'][6]['text']				= 'Health Fund Name';
		$this->memberRecord['attributes'][6]['value']				= '';
		$this->memberRecord['attributes'][7]['id']					= '13';
		$this->memberRecord['attributes'][7]['text']				= 'Health Fund Number';
		$this->memberRecord['attributes'][7]['value']				= '';
		$this->memberRecord['attributes'][8]['id']					= '14';
		$this->memberRecord['attributes'][8]['text']				= 'Individual Health Identifier (eHealth)';
		$this->memberRecord['attributes'][8]['value']				= '';
		
	}
	
	
	/**
	 * Build update member array based on details entered
	 */
	function buildUpdateMemberMedicAlertArray($_data){
	
		$check = false;
		// Website record
		//$this->memberRecord['webSiteRecord']['memberJoinDate']							= date('d M Y');
		//$this->memberRecord['webSiteRecord']['accountBalance'] 						= '0.00';
		//$this->memberRecord['webSiteRecord']['status']['statusTypeId'] 					= medicAlertApi::STATUS_ACTIVE_PENDING;
		//$this->memberRecord['webSiteRecord']['membership']['membershipTypeId']		= '6';
		//$this->memberRecord['webSiteRecord']['membership']['RenewalDate']				= '';
		
		$this->memberRecord['webSiteRecord']['pendingUpdate']							= '1';
		$this->memberRecord['webSiteRecord']['pendingUpdateDate']						= date('d/m/Y',time());
	
		$this->memberRecord['webSiteRecord']['details']['title']						= ucwords(strtolower($_data['user_title']));
		$this->memberRecord['webSiteRecord']['details']['firstName']					= ucwords(strtolower($_data['user_firstname']));
		$this->memberRecord['webSiteRecord']['details']['middleName']					= ucwords(strtolower($_data['user_middlename']));
		$this->memberRecord['webSiteRecord']['details']['surname']						= ucwords(strtolower($_data['user_lastname']));
		$this->memberRecord['webSiteRecord']['details']['nickName']						= ucwords(strtolower($_data['user_nickname']));
		$this->memberRecord['webSiteRecord']['details']['dateOfBirth']					= $_data['user_dob'];
		//$this->memberRecord['webSiteRecord']['details']['gender']						= ucwords(strtolower($_data['user_gender']));
		$this->memberRecord['webSiteRecord']['details']['phoneHome']					= $_data['user_phone_home'];
		$this->memberRecord['webSiteRecord']['details']['phoneWork']					= $_data['user_phone_work'];
		$this->memberRecord['webSiteRecord']['details']['phoneMobile']					= $_data['user_mobile'];
		$this->memberRecord['webSiteRecord']['details']['emailAddress']					= $_data['user_email'];
		$this->memberRecord['webSiteRecord']['details']['emergencyInformation']			= $_data['emergencyInfo'];
		$this->memberRecord['webSiteRecord']['details']['isOrganDonor']					= $_data['user_donor'];
		$this->memberRecord['webSiteRecord']['details']['donorFreeText']				= $_data['user_donorFreeText'];
		//$this->memberRecord['webSiteRecord']['details']['receiveMarketingMaterial']		= ($_data['check_newsletter']? '':'t');
		$this->memberRecord['webSiteRecord']['details']['correspondenceType']			= $_data['correspondenceType'];
		//$this->memberRecord['webSiteRecord']['details']['preferedPaymentMethod']			= $_data['user_preferedPaymentMethod'];
	
		$this->memberRecord['webSiteRecord']['address']['address']						= ucwords(strtolower($_data['user_address']));
		$this->memberRecord['webSiteRecord']['address']['suburb']						= ucwords(strtolower($_data['user_suburb']));
		$this->memberRecord['webSiteRecord']['address']['postCode']						= $_data['user_postcode'];
		$this->memberRecord['webSiteRecord']['address']['state']						= $_data['user_state_id'];
		$this->memberRecord['webSiteRecord']['address']['country']						= 'Australia';
		
		$this->memberRecord['webSiteRecord']['blood']['bloodGroup']						= $_data['blood_group'];
		$this->memberRecord['webSiteRecord']['blood']['bloodType']						= $_data['blood_group'];
		$this->memberRecord['webSiteRecord']['blood']['otherInfo']						= $_data['blood_group'];
	
		$this->memberRecord['webSiteRecord']['emergencyContact']['relationship']		= ucwords(strtolower($_data['contact_relationship']));
		$this->memberRecord['webSiteRecord']['emergencyContact']['name']				= ucwords(strtolower($_data['contact_name']));
		$this->memberRecord['webSiteRecord']['emergencyContact']['address']				= ucwords(strtolower($_data['contact_address']));
		$this->memberRecord['webSiteRecord']['emergencyContact']['suburb']				= ucwords(strtolower($_data['contact_suburb']));
		$this->memberRecord['webSiteRecord']['emergencyContact']['postCode']			= $_data['contact_postcode'];
		$this->memberRecord['webSiteRecord']['emergencyContact']['state']				= $_data['contact_state_id'];
		$this->memberRecord['webSiteRecord']['emergencyContact']['country']				= 'Australia';
		$this->memberRecord['webSiteRecord']['emergencyContact']['phoneHome']			= $_data['contact_phone_home'];
		$this->memberRecord['webSiteRecord']['emergencyContact']['phoneWork']			= $_data['contact_phone_work'];
		$this->memberRecord['webSiteRecord']['emergencyContact']['phoneMobile']			= $_data['contact_mobile'];
	
		$this->memberRecord['webSiteRecord']['doctor']['doctorName']					= ucwords(strtolower($_data['doc_name']));
		$this->memberRecord['webSiteRecord']['doctor']['medicalCentreName']				= ucwords(strtolower($_data['doc_medical_centre']));
		$this->memberRecord['webSiteRecord']['doctor']['address']						= ucwords(strtolower($_data['doc_address']));
		$this->memberRecord['webSiteRecord']['doctor']['suburb']						= ucwords(strtolower($_data['doc_suburb']));
		$this->memberRecord['webSiteRecord']['doctor']['postCode']						= $_data['doc_postcode'];
		$this->memberRecord['webSiteRecord']['doctor']['state']							= $_data['doc_state_id'];
		$this->memberRecord['webSiteRecord']['doctor']['country']						= 'Australia';
		$this->memberRecord['webSiteRecord']['doctor']['phoneNumber']					= $_data['doc_phone'];
		$this->memberRecord['webSiteRecord']['doctor']['fileNumber']					= $_data['doc_file_no'];

		$this->memberRecord['webSiteRecord']['conditions']								= $_data['conditions'];
		$this->memberRecord['webSiteRecord']['allergies']								= $_data['allergies'];
		$this->memberRecord['webSiteRecord']['medications']								= $_data['medications'];
	
		/* $this->memberRecord['webSiteRecord']['attributes'][0]['id']					= '7';
		$this->memberRecord['webSiteRecord']['attributes'][0]['text']					= 'Allergies Authenticated';
		$this->memberRecord['webSiteRecord']['attributes'][0]['value']					= ''; */
		/* $this->memberRecord['webSiteRecord']['attributes'][1]['id']					= '3';
		$this->memberRecord['webSiteRecord']['attributes'][1]['text']					= 'Authenticated (application form)';
		$this->memberRecord['webSiteRecord']['attributes'][1]['value']					= 'No'; */
		/* $this->memberRecord['webSiteRecord']['attributes'][2]['id']					= '5';
		$this->memberRecord['webSiteRecord']['attributes'][2]['text']					= 'Authenticated (MIR / FIR / AF)';
		$this->memberRecord['webSiteRecord']['attributes'][2]['value']					= '';
		$this->memberRecord['webSiteRecord']['attributes'][3]['id']					= '6';
		$this->memberRecord['webSiteRecord']['attributes'][3]['text']					= 'Authenticated (MIR / FIR / AF) Date (year only)';
		$this->memberRecord['webSiteRecord']['attributes'][3]['value']					= ''; */
		
		//NEW FIELDS 08/05/2014
		$this->memberRecord['webSiteRecord']['attributes'] = array();
		$this->memberRecord['webSiteRecord']['attributes'][0]['id']					= '10';
		$this->memberRecord['webSiteRecord']['attributes'][0]['text']				= 'DVA Gold Card Number';
		$this->memberRecord['webSiteRecord']['attributes'][0]['value']				= $_data['dvagoldcard'];
		$this->memberRecord['webSiteRecord']['attributes'][1]['id']					= '12';
		$this->memberRecord['webSiteRecord']['attributes'][1]['text']				= 'Health Fund Name';
		$this->memberRecord['webSiteRecord']['attributes'][1]['value']				= $_data['healthfundname'];
		$this->memberRecord['webSiteRecord']['attributes'][2]['id']					= '13';
		$this->memberRecord['webSiteRecord']['attributes'][2]['text']				= 'Health Fund Number';
		$this->memberRecord['webSiteRecord']['attributes'][2]['value']				= $_data['healthfundnumber'];
		$this->memberRecord['webSiteRecord']['attributes'][3]['id']					= '14';
		$this->memberRecord['webSiteRecord']['attributes'][3]['text']				= 'Individual Health Identifier (eHealth)';
		$this->memberRecord['webSiteRecord']['attributes'][3]['value']				= $_data['ehealth'];
	}
	
	
	/**
	 * Build and return the user array after login
	 * @param array $userData
	 * @return array 
	 */
	function getUserArray($userData){
		
		$user = array();
		$user['locked']                     = $userData['webSiteRecord']['pendingUpdate'];
		$user['lifetime']                   = $this->isLifetimeMember($userData['dataBaseRecord']['membership']['membershipTypeId']);
		$user['user_id']					= $userData['dataBaseRecord']['memberShipNumber'];
		$user['user_firstname']				= $userData['dataBaseRecord']['details']['firstName'];
		$user['user_lastname']				= $userData['dataBaseRecord']['details']['surname'];
		$user['user_nickname']				= $userData['dataBaseRecord']['details']['nickName'];
		$user['user_email']					= $userData['dataBaseRecord']['details']['emailAddress'];
		$user['user_address']				= $userData['dataBaseRecord']['address']['address'];
		$user['user_suburb']				= $userData['dataBaseRecord']['address']['suburb'];
		$user['user_state_id']				= $userData['dataBaseRecord']['address']['state'];
		$user['user_postcode']				= $userData['dataBaseRecord']['address']['postCode'];
		$user['user_phone_home']			= $userData['dataBaseRecord']['details']['phoneHome'];
		$user['user_mobile']				= str_replace(' ', '', $userData['dataBaseRecord']['details']['phoneMobile']);
		
		$user['user_status_db']             = $userData['dataBaseRecord']['status']['statusTypeId'];
		//$user['user_status']				= $userData['dataBaseRecord']['status']['statusTypeId']; // Had to change this because we need to track pending status of renewal paid members
		$user['user_status']				= $userData['webSiteRecord']['status']['statusTypeId'];
		$user['user_memberJoinDate']		= $userData['dataBaseRecord']['memberJoinDate'];
		$user['user_accountBalance']		= $userData['dataBaseRecord']['accountBalance'];
		$user['user_membershipType']		= $userData['dataBaseRecord']['membership']['membershipTypeId'];
		$user['user_membershipTypeText']	= $this->getMembershipTypeText($userData['dataBaseRecord']['membership']['membershipTypeId']);
		$user['user_RenewalDate']			= date('Y-m-d', strtotime($userData['dataBaseRecord']['membership']['RenewalDate']));
		$user['user_RenewalMonth']			= date("F", strtotime($userData['dataBaseRecord']['membership']['RenewalDate']));
		$user['preferedPaymentMethod']	    = $userData['dataBaseRecord']['details']['preferedPaymentMethod'];
		
		$user['WestpacCustomerNo']          = $userData['dataBaseRecord']['WestpacCustomerNo'];
		
		$user['autoBilling']                = $userData['dataBaseRecord']['details']['autoBilling'];
		$user['autoBillingMethod']          = $userData['dataBaseRecord']['details']['autoBillingMethod'];
		$user['autoBillingAcceptDate']      = $userData['dataBaseRecord']['details']['autoBillingAcceptDate'];
		$user['autoBillingIp']              = $userData['dataBaseRecord']['details']['autoBillingIp'];
		$user['autoBillingBankCustomerNo']  = $userData['dataBaseRecord']['details']['autoBillingBankCustomerNo'];

		//Calculate date difference between renewal date and today
		$renewalDate = date_create_from_format('Y-m-d', $user['user_RenewalDate']);
		$today = new DateTime();
		$interval = $renewalDate->diff($today);
		$year_diff = ceil(floatval($interval->format('%R%y.%m%d')));
		$day_diff = floatval($interval->format('%R%a'));
		
		//Verify if member requires reactivation
		$user['reactivation'] = 'f';
		if($year_diff > 1){
		  //Add reactivation fee when year difference is greater than 2
		  $user['reactivation'] = 't';
		}
		
		//Verify if member can renew the membership
		$user['renew'] = 'f';
		
	    if($day_diff >= -30 && $user['autoBilling'] != 't'){
		  //Allow renew before 30 days of renewal date
		  $user['renew'] = 't';
		}
		
		$user = stripslashes_deep($user);
		return $user;
	}
	
	
	/**
	 * Returns the membership type text based on the membership type id and the constants set in the API
	 */
	function getMembershipTypeText($mtid){
		
		switch($mtid){
			case '1': return 'Lifetime member';//'Grandfather member';
			case '2': return 'Annual member';//'Click over member';
			case '3': return 'Annual member';
			case '4': return 'Lifetime member';//'Business member';
			case '5': return 'Lifetime member';
			case '6': return 'Benevolent member';//'Benevolent member';
			case '7': return 'Annual member';//'Benevolent annual member';
			default:  return '';
		}
		
	}
	
	
	function setPendingStatus($_token, $_memberId){
		if($this->setSessionVars($_token)){
		  $this->memberRecord['webSiteRecord']['status']['statusTypeId'] = medicAlertApi::STATUS_ACTIVE_PENDING;
		  try{
		    if($this->memberRecord['dataBaseRecord']['memberShipNumber'] !== $_memberId){
		      $this->logOut($_token);
		    }else{
		      $memberUpdated = $this->medicAlertApi->memberUpdate($_token, json_encode($this->memberRecord));
		      return true;
		    }
		  }catch(exceptionMedicAlertApiNotAuthenticated $e){
		    $this->errorMsg = "API error: {$e}";
		  }catch(exceptionMedicAlertApiSessionExpired $e){
		    $this->errorMsg = "API error: {$e}";
		  }catch(exceptionMedicAlertApi $e){
		    $this->errorMsg = "API error: {$e}";
		  }
		}
		return false;
	}
	
	
	function setAutoRenewal($_token, $_data){
	  if($this->setSessionVars($_token)){
	    $this->memberRecord['webSiteRecord']['status']['statusTypeId'] = medicAlertApi::STATUS_ACTIVE_PENDING;
	    $this->memberRecord['webSiteRecord']['details']['autoBilling']                  = 't';
	    $this->memberRecord['webSiteRecord']['details']['autoBillingMethod']            = (($_data['method'] == 'bankAccount') ? 'Directdebit' : 'Creditcard') ;
	    $this->memberRecord['webSiteRecord']['details']['autoBillingAcceptDate']        = date('Y-m-d');
	    $this->memberRecord['webSiteRecord']['details']['autoBillingIp']                = $_SERVER['REMOTE_ADDR'];
	    $this->memberRecord['webSiteRecord']['details']['autoBillingBankCustomerNo']    = $_data['bank_customer_id'];
	    try{
	      if($this->memberRecord['dataBaseRecord']['memberShipNumber'] !== $_data['user_id']){
	        $this->logOut($_token);
	      }else{
	        $memberUpdated = $this->medicAlertApi->memberUpdate($_token, json_encode($this->memberRecord));
	        return true;
	      }
	    }catch(exceptionMedicAlertApiNotAuthenticated $e){
	      $this->errorMsg = "API error: {$e}";
	    }catch(exceptionMedicAlertApiSessionExpired $e){
	      $this->errorMsg = "API error: {$e}";
	    }catch(exceptionMedicAlertApi $e){
	      $this->errorMsg = "API error: {$e}";
	    }
	  }
	  return false;
	}
	
	
	function processUpdate($_token, $_data){
		try{
          $this->buildUpdateMemberMedicAlertArray($_data);
          $t_arr = unclean($this->memberRecord);
          if($memberUpdated = $this->medicAlertApi->memberUpdate($_token, json_encode($t_arr))){
            return true;
          }
          $this->errorMsg = "Unknown error. Please contact us.";
		  
		}catch(exceptionMedicAlertApiNotAuthenticated $e){
          $this->errorMsg = "API error: {$e}";
		}catch(exceptionMedicAlertApiSessionExpired $e){
          $this->errorMsg = "API error: {$e}";
		}catch(exceptionMedicAlertApi $e){
          $this->errorMsg = "API error: {$e}";
		}
		return false;
	}
	
	
	function UpdatePassword($_token, $_currentpwd, $_newpwd){
		try{
			if($results = $this->medicAlertApi->updatePassWord($_token, $_currentpwd, $_newpwd)){
			  //Password successfully updated
			  return true;
			}
			
		}catch(exceptionMedicAlertNotFound $e){ // may be a different exception to catch, but this is what the example used.
		  $this->errorMsg = "Incorrect password, password could not be updated because current password does not match.";
		}
		catch(exceptionMedicAlertApiNotAuthenticated $e){
		  $this->errorMsg = "API error: {$e}";
		}
		catch(exceptionMedicAlertApiSessionExpired $e){
		  $this->errorMsg = "API error: {$e}";
		}
		catch(exceptionMedicAlertApi $e){
		  $this->errorMsg = "API error: {$e}";
		}
		return false;
	}
	
	
	
	/**
	 * Formats the date of birth from YYYY-MM-DD to DD-MM-YYYY
	 * @param string $date
	 * @return string
	 */
	function formatDateToForward($date){
		
		$formattedDate = substr($date, 8, 2).'-';
		$formattedDate.= substr($date, 5, 2).'-';
		$formattedDate.= substr($date, 0, 4);
		return $formattedDate;
		
	}
	
	
	
	/**
	 * Determines which conditions/allergies are set in the member record for the array that's sent over, and returns them as a list
	 * @param array $array_field
	 * @return string
	 */
	function getArrayField($array_field){
	
		$arr_size = count($array_field);
		$output = '';
	
		for($i = 0; $i < $arr_size; $i++){
			if($array_field[$i]['status'] != "4"){
				$output.= $array_field[$i]['value'].'<br />';
			}
		}
	
		return $output;
	
	}	
	
	
	function getMobileArrayField($array_field, $name){
	
		$arr_size = count($array_field);
		$output = '';
	
		$n = 0;
		foreach($array_field as $value){
			$n++;
			$output.= '<label for="'.$name.$n.'">'.$value['value'].'</label><input id="'.$name.$n.'" class="spc-checkbox" name="'.$name.'[]" type="checkbox" value="'.$value['value'].'" checked="checked" readonly="readonly" disabled="disabled" />';
		}
	
		return $output;
	
	}	
	
	
	/**
	 * Determines which conditions/allergies are set in the member record for the array that's sent over, and returns them as options for a select list
	 * @param array $array_field
	 * @return string
	 */
	function getArrayFieldOptions($array_field){
		
		$arr_size = count($array_field);
		$output = '';
		
		for($i = 0; $i < $arr_size; $i++){
			$output.= '<option value="'.$array_field[$i]['value'].'"'.(($array_field[$i]['status'] == "1") ? ' selected' : '').'>'.$array_field[$i]['value'].'</option>';
		}

		return $output;
		
	}
	
	
	/**
	 * Outputs a list of checkboxes based on an array and matches them to the full array of options and sets the matched checkboxes to checked.
	 * @param array $array_field
	 * @param string $name
	 * @param array $array_match
	 * @return string
	 */
	function getArrayFieldTickBoxes($array_field, $name, $array_match){
	
		$array_match_size = count($array_match);
		$arr_size = count($array_field);
		$output = '';
		
		$other = "";
		foreach($array_field as $value){
				$output.= '<input class="spc-checkbox" name="'.$name.'[]" type="checkbox" value="'.$value['value'].'" checked />'.$value['value'].'<br />';
		}
		
		$output .= '<textarea class="update-textarea" name="'.$name.'[]" id="'.$name.'">'.$other.'</textarea>';
		
		return $output;
	
	}
	
	
	function getMobileArrayFieldTickBoxes($array_field, $name, $array_match){
	
		$array_match_size = count($array_match);
		$arr_size = count($array_field);
		$output = '';
		
		$other = "";
		$n = 0;
		foreach($array_field as $value){
			$n++;
			$output.= '<label for="'.$name.$n.'">'.$value['value'].'</label><input id="'.$name.$n.'" class="spc-checkbox" name="'.$name.'[]" type="checkbox" value="'.$value['value'].'" checked />';
		}
		
		$output .= '<textarea class="update-textarea" name="'.$name.'[]" id="'.$name.'">'.$other.'</textarea>';
		
		return $output;
	
	}
	
	
	function getArrayFieldTickBoxes2($array_field, $name, $array_match){
	
		$array_match_size = count($array_match);
		$arr_size = count($array_field);
		$output = '';
	
		for($i = 0; $i < $array_match_size; $i++){
			if($arr_size > 0){
				for($j = 0; $j < $arr_size; $j++){
					if($array_match[$i] == $array_field[$j]['value']){
						$output.= '<input name="'.str_replace(" ", "_", str_replace("/", "_", $array_match[$i])).'" type="checkbox" value="1"'.(($array_field[$j]['status'] == "1") ? ' checked' : '').' />'.$array_match[$i].'<br />';
					}
				}
			}else{
				$output.= '<input name="'.str_replace(" ", "_", str_replace("/", "_", $array_match[$i])).'" type="checkbox" value="1" />'.$array_match[$i].'<br />';
			}
		}
		return $output;
	
	}
	
	
	/**
	 * Sets the allow update flag and the please note message based on the user status
	 */
	function checkMemberStatus(){
		
		$_SESSION['user']['allowupdate'] = true;
		$_SESSION['user']['pleasenote'] = '';

		if($this->memberRecord['webSiteRecord']['pendingUpdate']){ // if updates pending
			$_SESSION['user']['allowupdate'] = false;
			$_SESSION['user']['pleasenote'].= '<p class="large"><span class="highlight">Your updates are currently pending.</span> You can still <a href="/Products">continue to shop</a>.</p>
											   <br />
											   <p class="small">You are unable to make changes to your \'Personal\' details until your changes made on '.$this->memberRecord['webSiteRecord']['pendingUpdateDate'].' have been reviewed and updated by our Membership Services team.</p>';
		}else{
			$_SESSION['user']['allowupdate'] = true;
		}
		
	}
	
	
	
	
	/**
	 * Determines if the member is a lifetime (pay as you go) member or not
	 * @return boolean
	 */
	function isLifetimeMember($_user_membershipType){

		if(	$_user_membershipType == medicAlertApi::MEMBERSHIP_GRANDFATHER ||
			$_user_membershipType == medicAlertApi::MEMBERSHIP_BUSINESS ||
			$_user_membershipType == medicAlertApi::MEMBERSHIP_LIFETIME ||
			$_user_membershipType == medicAlertApi::MEMBERSHIP_BENOVOLENT ){
			return true;
		}else{
			return false;
		}
		
	}
	
	
	/**
	 * Determines if the member is an annual (annual renewal) member or not
	 * @return boolean
	 */
	function isAnnualMember($_user_membershipType){
		
		if(	$_user_membershipType == medicAlertApi::MEMBERSHIP_CLICKOVER ||
			$_user_membershipType == medicAlertApi::MEMBERSHIP_ANNUAL ||
			$_user_membershipType == medicAlertApi::MEMBERSHIP_BENOVOLENT_ANNUAL ){
			return true;
		}else{
			return false;
		}

	}
	
	
	/**
	 * Determines if the member status is active or not
	 * @return boolean
	 */
	function isActiveMember($_statusTypeId){
	
		if($_statusTypeId == medicAlertApi::STATUS_ACTIVE){
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	 * Determines if the member status is active or not
	 * @return boolean
	 */
	function isInactiveMember($_statusTypeId){
	
		if($_statusTypeId == medicAlertApi::STATUS_UNCLAIMED
		|| $_statusTypeId == medicAlertApi::STATUS_NO_LONGER_REQUIRED
		|| $_statusTypeId == medicAlertApi::STATUS_DECEASED){
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	 * Determines if the member status is unfinancial or not
	 * @return boolean
	 */
	function isUnfinancialMember($_statusTypeId){

		if($_statusTypeId == medicAlertApi::STATUS_UNFINANCIAL || $this->memberRecord['webSiteRecord']['status']['statusTypeId'] == medicAlertApi::STATUS_UNFINANCIAL ){
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	 * Determines if the member status is unfinancial or not
	 * @return boolean
	 */
	function isRenewalMonth(){
		$renewal_month = date("m Y", strtotime($this->memberRecord['dataBaseRecord']['membership']['RenewalDate']));
		$current_month = date("m Y", time());
		if($renewal_month == $current_month){
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	 * Determines if the member status is unfinancial or not
	 * @return boolean
	 */
	function isPastRenewalMonth($_renewalDate){
		$renewal_year = date("Y", strtotime($_renewalDate));
		$current_year = date("Y", time());
		$year_diff = ($current_year - $renewal_year) * 12; //Number of years difference times 12 months in a year to get difference in months
		$renewal_month = date("m", strtotime($_renewalDate));
		$current_month = date("m", time());
		$month_diff = $current_month - $renewal_month + 1;
		$diff = $year_diff + $month_diff;
		if($diff > 0){
			return $diff;
		}else{
			return 0;
		}
	}
	
	
	/**
	 * Determines if the member status is pending or not
	 * @return boolean
	 */
	function isPendingMember($_statusTypeId){
		if($_statusTypeId == medicAlertApi::STATUS_ACTIVE_PENDING){ 
			return true;
		}else{
			return false;
		}
	
	}
	
	
	/**
	 * Determines if the member already has Direct Debit enabled
	 * @return boolean
	 */
	function isDirectDebitMember(){

	  if($_SESSION['user']['preferedPaymentMethod'] == "Direct Debit" || $this->memberRecord['dataBaseRecord']['details']['preferedPaymentMethod'] == "Direct Debit" || $this->memberRecord['webSiteRecord']['details']['preferedPaymentMethod'] == "Direct Debit"){ // from webSiteRecord
	    return true;
	  }else{
	    return false;
	  }
	
	}
	
	
	/**
	 * Print New Member registration email
	 */
	function newMemberEmail($member_name, $memberShipNumber){
		set_include_path($_SERVER['DOCUMENT_ROOT']);
	  ob_start();
	  include 'members/new-member-email.php';
	  $buf = ob_get_clean();
		return $buf;
	}
	
	
	/**
	 * The error message 
	 * @return string
	 */
	function getErrorMsg(){
	  return $this->errorMsg;
	}
	
	
	/**
	 * The variables and values to save in session
	 * @return array
	 */
	function getSessionVars(){
	  return $this->sessionVars;
	}

	
	/**
	 * retrieve file for member
	 * @return array
	 */
	function getMembersFile($_token, $_fileId){
  	  $this->errorMsg = null;
      $fileArr = array();
      try{
        $memberFileRecord = $this->medicAlertApi->fileRetrieve($_token, $_fileId);
        $fileArr = json_decode($memberFileRecord, TRUE);
      }
      catch(exceptionMedicAlertApiNotAuthenticated $e){
        $this->errorMsg = "API error: {$e}";
      }
      catch(exceptionMedicAlertApiSessionExpired $e){
        $this->errorMsg = "API error: {$e}";
      }
      catch(exceptionMedicAlertApi $e){
        $this->errorMsg = "API error: {$e}";
      }
      return $fileArr;
	}
	
	
	private function buildMemberProfileArray($_data){
	  $resArr = array();
	  $resArr['user_title'] = $_data['details']['title'];
	  $resArr['user_firstname'] = $_data['details']['firstName'];
	  $resArr['user_middlename'] = $_data['details']['middleName'];
	  $resArr['user_lastname'] = $_data['details']['surname'];
	  $resArr['user_nickname'] = $_data['details']['nickName'];
	  $resArr['user_email'] = $_data['details']['emailAddress'];
	  $resArr['user_address'] = $_data['address']['address'];
	  $resArr['user_suburb'] = $_data['address']['suburb'];
	  $resArr['user_state_id'] = $_data['address']['state'];
	  $resArr['user_state'] = $_data['address']['state'];
	  $resArr['user_postcode'] = $_data['address']['postCode'];
	  $resArr['user_phone_home'] = $_data['details']['phoneHome'];
	  $resArr['user_phone_work'] = $_data['details']['phoneWork'];
	  $resArr['user_mobile'] = $_data['details']['phoneMobile'];
	  $resArr['user_dob'] = $_data['details']['dateOfBirth'];
	  $resArr['user_gender'] = $_data['details']['gender'];
	  $resArr['receiveMarketingMaterial'] = $_data['details']['receiveMarketingMaterial'];
	  $resArr['correspondenceType'] = $_data['details']['correspondenceType'];
	  $resArr['preferedPaymentMethod'] = $_data['details']['preferedPaymentMethod'];
	  $resArr['user_heardabout'] = $_data['attributes'][4]['value'];
	   
	  $resArr['user_donor'] = $_data['details']['isOrganDonor'];
	  $resArr['user_donorFreeText'] = "";
	  if($_data['details']['donorEye'] == 't'){
	    $resArr['user_donorFreeText'] .= "Eye, ";
	  }
	  if($_data['details']['donorHeart'] == 't'){
	    $resArr['user_donorFreeText'] .= "Heart, ";
	  }
	  if($_data['details']['donorKidney'] == 't'){
	    $resArr['user_donorFreeText'] .= "Kidney, ";
	  }
	  if($_data['details']['donorAny'] == 't'){
	    $resArr['user_donorFreeText'] .= "Any, ";
	  }
	  $resArr['user_donorFreeText'] .= unclean($_data['details']['donorFreeText']);
	  $resArr['check_newsletter'] = $_data['details']['receiveMarketingMaterial'];
	  
	  $resArr['emergencyInfo'] = unclean($_data['details']['emergencyInformation']);
	  $resArr['contact_name'] = $_data['emergencyContact']['name'];
	  $resArr['contact_relationship'] = $_data['emergencyContact']['relationship'];
	  $resArr['contact_address'] = unclean($_data['emergencyContact']['address']);
	  $resArr['contact_suburb'] = unclean($_data['emergencyContact']['suburb']);
	  $resArr['contact_postcode'] = unclean($_data['emergencyContact']['postCode']);
	  $resArr['contact_state_id'] = unclean($_data['emergencyContact']['state']);
	  $resArr['contact_country'] = unclean($_data['emergencyContact']['country']);
	  $resArr['contact_phone_home'] = $_data['emergencyContact']['phoneHome'];
	  $resArr['contact_phone_work'] = $_data['emergencyContact']['phoneWork'];
	  $resArr['contact_mobile'] = str_replace(' ', '', $_data['emergencyContact']['phoneMobile']);
	  
	  $resArr['doc_name'] = unclean($_data['doctor']['doctorName']);
	  $resArr['doc_medical_centre'] = unclean($_data['doctor']['medicalCentreName']);
	  $resArr['doc_address'] = unclean($_data['doctor']['address']);
	  $resArr['doc_suburb'] = unclean($_data['doctor']['suburb']);
	  $resArr['doc_postcode'] = unclean($_data['doctor']['postCode']);
	  $resArr['doc_state_id'] = unclean($_data['doctor']['state']);
	  $resArr['doc_country'] = unclean($_data['doctor']['country']);
	  $resArr['doc_phone'] = $_data['doctor']['phoneNumber'];
	  $resArr['doc_file_no'] = $_data['doctor']['fileNumber'];
	  
	  $resArr['conditions'] = $_data['conditions'];
	  $resArr['allergies'] = $_data['allergies'];
	  $resArr['medications'] = $_data['medications'];
	  $resArr['other'] = '';
	  $resArr['records'] = '';
	  
	  $resArr['blood_group'] = $_data['blood']['bloodGroup'];
	  
	  // $resArr['profileImage'] = $_data['profileImage'];
	  $resArr['medicalRecordFiles'] = $_data['attachments']['medicalRecordFiles'];
	  $resArr['otherFiles'] = $_data['attachments']['otherFiles'];
	  
	  foreach($_data['attributes'] as $at){
	    $resArr['attributes']["{$at['id']}"] = $at['value'];
	  }
	  return $resArr;
	}
	
	
	function formatProfileArrayField($_newArr, $_existingArr = array()){
	  $resArr = array();
	  $temp = array();
	  $existingArr = array();
	  
	  foreach($_existingArr as $value){
	    $existingArr[] = $value['value'];
	  }
	  
	  foreach($_newArr as $value){
	    if(empty($value)){ continue; }
	
	    $t_ar = preg_split("/\\r\\n|\\r|\\n|;|,/",$value);
	    foreach($t_ar as $t_v){
	      $t_value = unclean($t_v);
	      if(empty($t_value)){ continue; }
	      if(in_array($t_value, $existingArr)){
	        $temp[] = $t_value;
	        $resArr[] 	= array("status" =>  medicAlertApi::CMA_EXISTING, "value" => $t_v);
	      }else{
	        $temp[] = $t_value;
	        $resArr[] 	= array("status" => medicAlertApi::CMA_NEW, "value" => $t_v);
	      }
	    }
	  }
	  foreach($existingArr as $value){
	    if(in_array($value, $temp)){
	    }else{
	      $resArr[] = array("status" => medicAlertApi::CMA_DELETED, "value" => $value);
	    }
	  }
	  return $resArr;
	}
	
	
	function printProfile($_resourcesPath, $_mainData, $_data){
	  $conditions = array();
	  foreach($_data['conditions'] as $rec){
	    $conditions[] = $rec['value'];
	  }
	  $allergies = array();
	  foreach($_data['allergies'] as $rec){
	    $allergies[] = $rec['value'];
	  }
	  $medications = array();
	  foreach($_data['medications'] as $rec){
	    $medications[] = $rec['value'];
	  }
	  
	  $buf = '<!DOCTYPE html>
        		<html>
        		<head>
        			<meta charset="utf-8" />
        			<title>Member Information</title>
        			<!--[if IE]>
        				<script src="'.$_resourcesPath.'/html5shiv.js"></script>
        			<![endif]-->
        			<link type="text/css" href="'.$_resourcesPath.'/print_styles.css" rel="stylesheet">
        		</head>
        		<body>
        		<div id="print"><a href="javascript:void(0);" onclick="window.print();" style="color:#ED174F">PRINT</a></div>
        <div id="logo"><img src="'.$_resourcesPath.'/medic-alert-logo.jpg" style="width: 70%;" /></div>
        <div class="clear"></div>
        	  
        <div class="header">
        	<h1 class="left">Member <br />Information</h1>
            <div class="member-info right">
            	<table cellpadding="0" cellspacing="0" border="0" width="100%">
                	<tr valign="top">
                    	<td class="title" width="38%">Member Number</td>
                    	<td class="data">'.unclean($_mainData['user_id']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Membership Type</td>
                    	<td class="data">'.unclean($_mainData['user_membershipTypeText']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title last">Renewal Date</td>
                    	<td class="data last">'.unclean(date('d M Y', strtotime($_mainData['user_RenewalDate']))).'</td>
                    </tr>
                </table>
            </div>
            <div class="clear"></div>
        </div>
        	  
        <div class="data-container">
        	<div class="left">
            	<table cellpadding="0" cellspacing="0" border="0" width="100%">
                	<tr valign="top">
                    	<th colspan="2">Your personal information</th>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Title:</td>
                    	<td>'.unclean($_data['user_title']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">First Name:</td>
                    	<td>'.unclean($_data['user_firstname']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Middle Name:</td>
                    	<td>'.unclean($_data['user_middlename']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Last Name:</td>
                    	<td>'.unclean($_data['user_lastname']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Email Address:</td>
                    	<td>'.unclean($_data['user_email']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Street Address:</td>
                    	<td>'.unclean($_data['user_address']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Suburb:</td>
                    	<td>'.unclean($_data['user_suburb']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">State:</td>
                    	<td>'.unclean($_data['user_state']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Postcode:</td>
                    	<td>'.unclean($_data['user_postcode']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Home Phone:</td>
                    	<td>'.unclean($_data['user_phone_home']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Work Phone:</td>
                    	<td>'.unclean($_data['user_phone_work']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Mobile Phone:</td>
                    	<td>'.unclean($_data['user_mobile']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Date of Birth:</td>
                    	<td>'.unclean($_data['user_dob']).'</td>
                    </tr>
                </table>
            </div>
        	  
            <div class="right">
            	<table cellpadding="0" cellspacing="0" border="0" width="100%">
                	<tr valign="top">
                    	<th colspan="2">Emergency contact</th>
                    </tr>
                	<tr valign="top">
                    	<td class="title" width="45%">Name:</td>
                    	<td>'.unclean($_data['contact_name']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Relationship:</td>
                    	<td>'.unclean($_data['contact_relationship']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Street Address:</td>
                    	<td>'.unclean($_data['contact_address']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Suburb:</td>
                    	<td>'.unclean($_data['contact_suburb']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">State:</td>
                    	<td>'.unclean($_data['contact_state_id']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Postcode:</td>
                    	<td>'.unclean($_data['contact_postcode']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Home Phone:</td>
                    	<td>'.unclean($_data['contact_phone_home']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Work Phone:</td>
                    	<td>'.unclean($_data['contact_phone_work']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Mobile Phone:</td>
                    	<td>'.unclean($_data['contact_mobile']).'</td>
                    </tr>
                </table>
        	  
            	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="doctor">
                	<tr valign="top">
                    	<th colspan="2">Doctor contact</th>
                    </tr>
                	<tr valign="top">
                    	<td class="title" width="45%">Name:</td>
                    	<td>'.unclean($_SESSION['doc']['name']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Medical Centre:</td>
                    	<td><span class="title">'.unclean($_SESSION['doc']['medical_centre']).'</span></td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Street Address:</td>
                    	<td>'.unclean($_SESSION['doc']['address']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Suburb:</td>
                    	<td>'.unclean($_SESSION['doc']['suburb']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">State:</td>
                    	<td>'.unclean($_SESSION['doc']['state_id']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Postcode:</td>
                    	<td>'.unclean($_SESSION['doc']['postcode']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">Phone:</td>
                    	<td>'.unclean($_SESSION['doc']['phone']).'</td>
                    </tr>
                	<tr valign="top">
                    	<td class="title">File Number:</td>
                    	<td><span class="title">'.unclean($_data['file_no']).'</span></td>
                    </tr>
                </table>
            </div>
        	  
            <div class="clear"></div>
        	  
            <br><br>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr valign="top">
                    <th colspan="2">Your medical information</th>
                </tr>
                <tr valign="top">
                    <td class="title" width="15%">Medical Conditions:</td>
                    <td>'.implode(", ", $conditions ).'</td>
                </tr>
                <tr valign="top">
                    <td class="title" width="15%">Allergies:</td>
                    <td>'.implode(", ", $allergies ).'</td>
                </tr>
                <tr valign="top">
                    <td class="title" width="15%">Medications:</td>
                    <td>'.implode(", ", $medications ).'</td>
                </tr>
                <tr valign="top">
                    <td class="title" width="15%">Emergency Information:</td>
                    <td>'.unclean($_data['emergencyInfo']).'</td>
                </tr>
                <tr valign="top">
                    <td class="title" width="15%">Blood Type:</td>
                    <td>'.unclean($_data['blood_group']).'</td>
                </tr>
            </table>
            <br><br>
        	  
        	  
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr valign="top">
                    <th colspan="8">Organ/Tissue Donation</th>
                </tr>
                <tr valign="top">
                    <td class="title" width="10%">Donor Status:</td>
                    <td>'.($_data['user_donor'] == 't' ? 'Yes':'No').'</td>
                    <td class="title" width="10%">&nbsp;</td>
                    <td class="title" width="10%">Other Info:</td>
                    <td><div class="donor_free_text">'.unclean($_data['user_donorFreeText']).'</div></td>
                </tr>
            </table>
            <br><br>
        	  
        	  
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr valign="top">
                    <th>Medical authentication <span>(this is optional but highly recommended)</span></th>
                </tr>
                <tr valign="top">
                	<td>
                	  <div class="notice">
                		Your immediate protection is our highest priority and MedicAlert Foundation understands that it is not always convenient to obtain your healthcare provider\'s signature to authenticate your medical information immediately. Please consider having this information confirmed the next time you visit your doctor. MedicAlert Foundation reserves the right to request specific healthcare provider authentication for individual medical conditions at the time of joining.
                	  </div>
                	  <div class="clear"></div>
                      <div class="left">
                        	<span style="color:#ED174F;">Doctor/Healthcare Provider</span> &mdash; To the best of my ability I believe that the medical information provided is current and correct.<br>
                        	<br>
                    		<span style="color:#ED174F;">Profession:</span>
        	  
                      </div>
                      <div class="right">
                      	<div class="signature">Doctor/healthcare provider\'s signature</div>
                      </div>
                      <div class="clear"></div>
                      <div class="date">Date<img src="'.$_resourcesPath.'/date.jpg" /></div>
                  </td>
                </tr>
            </table>
        </div>
        	  
        <div class="footer">
        	<strong>MEMBERSHIP SERVICES CALL 1800 88 22 22</strong> (Mon-Fri, 9am to 5pm CST)
            <div class="clear"></div>
        </div>
        	  
        </body>
        </html>';
	  return $buf;
	}
	
	/**
	 * FOR WEBSITE ONLY
	 * Insert new address in tbl_address and returns address_id
	 * Require associative array: (address_user_id, address_name, address_telephone, address_mobile, address_line1, address_line2,
	 address_suburb, address_state, address_country, address_postcode)
	 * @param array $addressArr
	 * @return int
	 */
	function InsertNewAddress($addressArr){
      $params = array(
          ":address_user_id" => $addressArr['address_user_id'], 
          ":address_name" => (empty($addressArr['address_name'])? '' : $addressArr['address_name']), 
          ":address_surname" => (empty($addressArr['address_surname'])? '' : $addressArr['address_surname']), 
          ":address_email" => (empty($addressArr['address_email'])? '' : $addressArr['address_email']), 
          ":address_telephone" => (empty($addressArr['address_telephone'])? '' : $addressArr['address_telephone']), 
          ":address_mobile" => (empty($addressArr['address_mobile'])? '' : $addressArr['address_mobile']), 
          ":address_line1" => (empty($addressArr['address_line1'])? '' : $addressArr['address_line1']), 
          ":address_line2" => (empty($addressArr['address_line2'])? '' : $addressArr['address_line2']), 
          ":address_suburb" => (empty($addressArr['address_suburb'])? '' : $addressArr['address_suburb']), 
          ":address_state" => (empty($addressArr['address_state'])? '' : $addressArr['address_state']), 
          ":address_country" => (empty($addressArr['address_country'])? '' : $addressArr['address_country']), 
          ":address_postcode" => (empty($addressArr['address_postcode'])? '' : $addressArr['address_postcode']) 
      );
      $sql = "SELECT address_id FROM tbl_address WHERE
          address_user_id = :address_user_id AND address_name = :address_name AND address_surname = :address_surname AND
          address_email = :address_email AND address_telephone = :address_telephone AND address_mobile = :address_mobile AND
          address_line1 = :address_line1 AND address_line2 = :address_line2 AND address_suburb = :address_suburb AND
          address_state = :address_state AND address_country = :address_country AND address_postcode = :address_postcode AND
          address_deleted IS NULL";
      
      if($res = $this->DBobj->wrappedSql($sql, $params)){
        $sql = "UPDATE tbl_address SET address_modified = now()  WHERE address_id = :id ";
        $this->DBobj->wrappedSql($sql, array(':id' => $res[0]['address_id']));
        return $res[0]['address_id'];
      } else{
        $sql = " INSERT INTO tbl_address (
            address_user_id, address_name, address_surname, address_email, address_telephone, address_mobile, address_line1,
            address_line2, address_suburb, address_state, address_country, address_postcode,	address_created
          ) VALUES (
            :address_user_id, :address_name, :address_surname, :address_email, :address_telephone, :address_mobile, :address_line1,
            :address_line2, :address_suburb, :address_state, :address_country, :address_postcode,	now()
          )";
        
        if($this->DBobj->wrappedSql($sql, $params)){
          return $this->DBobj->wrappedSqlIdentity();
        }
      }
      return 0;
	}
	
	
	
	/**
	 * Create new MAF member, returns new member's id 
	 * @return int
	 */
	function CreateMember($_data){
	  $this->errorMsg = null;
	  try{
	    $authJSONResponse = $this->medicAlertApi->authenticate(medicAlertApi::API_USER, medicAlertApi::API_USER_PASSWORD);
	    $authenticationRecord = json_decode($authJSONResponse, true);
	    $this->token = $authenticationRecord['sessionToken'];
	  }
	  catch(Exception $e){
	    $this->errorMsg = "Invalid API membership number/password.";
	  }
	
	  // set up the create member record
	  $this->buildNewMemberMedicAlertArray($_data);
	
	  // json encode it
	  $t_arr = unclean($this->memberRecord);
	  $memberCreateRecord = json_encode($t_arr);
	
	  try{
        // create a new member in the database (this returns the membership number)
        $memberCreateResult = json_decode($this->medicAlertApi->memberCreate($this->token, $memberCreateRecord), true);
	  }
	  catch(Exception $e){
	    $this->errorMsg = "Error while creating new member. {$e}";
	  }
	  
	  // log out of the API (as we are logged in as the MA-WEBSITE user
	  $this->medicAlertApi->logout($this->token);
		
	  return (empty($memberCreateResult['memberShipNumber']) ? 0 : $memberCreateResult['memberShipNumber']);
	}
	
	
	/**
	 * FOR WEBSITE ONLY
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
   * ONLY FOR GUEST - Insert a new record in tbl_user and return associative array: ('id', 'gname', 'surname', 'email')
   * On error return associative array: ('error')
   * Require associative array: ('gname', 'surname', 'password', 'email')
   *
   * @param array $user          
   * @return array
   */
  function CreateGuest($user){
    $temp_str = getPass(time() . '@@' . $user['email'], genRandomString(10));
    $params = array(
        ":username" => time() . '@@' . $user['email'], 
        ":gname" => $user['gname'], 
        ":surname" => (empty($user['surname'])? '' : $user['surname']), 
        ":email" => $user['email'], 
        ":password" => $temp_str, 
        ":mobile" => $user['mobile'],
        ":dob" => $user['db_dob'],
        ":gender" => $user['gender'],
        ":heardabout" => $user['heardabout'],
        ":user_site" => $this->site, 
        ":email_promo" => (empty($user['want_email_promo'])? 0 : 1), 
        ":sms_promo" => (empty($user['want_sms_promo'])? 0 : 1), 
        ":ip" => $_SERVER['REMOTE_ADDR'], 
        ":browser" => $_SERVER['HTTP_USER_AGENT'] 
    );
    
    $sql = "INSERT INTO tbl_user (user_username, user_gname, user_surname, user_email, user_password, user_mobile, user_dob, user_gender, user_heardabout, user_site, user_email_promo, user_sms_promo, user_ip, user_browser, user_created)
					 values ( :username, :gname, :surname, :email, :password, :mobile, :dob, :gender, :heardabout, :user_site, :email_promo, :sms_promo, :ip, :browser, now() )";
    if($this->DBobj->wrappedSql($sql, $params)){
      $userId = $this->DBobj->wrappedSqlIdentity();
      $this->user_id = $userId;
      $result = array(
          "id" => $userId, 
          "gname" => $user['gname'], 
          "surname" => $user['surname'], 
          "email" => $user['email'] 
      );
    } else{
      $this->user_id = 0;
      $result = array(
          'error' => 'There was a connection problem. Please, try again!' 
      );
    }
    return $result;
  }
	

}

function stripslashes_deep($value){
  $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
  return $value;
}

?>
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
    
    if(empty($this->errorMsg) && $this->initSessionVars()){
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
   * This session array is used to prefill the update personal/medical details form.
   */
  function initSessionVars(){
    $this->errorMsg = null;
    try{
      $this->memberRecordJSONResponse = $this->medicAlertApi->memberRetrieve($this->token);
      $this->memberRecord = json_decode($this->memberRecordJSONResponse, true);
      $this->memberRecord = stripslashes_deep($this->memberRecord);
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
      
      $this->sessionVars['token'] = $this->token;
      
      $this->sessionVars['main'] = $this->getUserArray($this->memberRecord, $userName);
      
      $this->sessionVars['update']['user_title'] = $this->memberRecord['dataBaseRecord']['details']['title'];
      $this->sessionVars['update']['user_firstname'] = $this->memberRecord['dataBaseRecord']['details']['firstName'];
      $this->sessionVars['update']['user_middlename'] = $this->memberRecord['dataBaseRecord']['details']['middleName'];
      $this->sessionVars['update']['user_lastname'] = $this->memberRecord['dataBaseRecord']['details']['surname'];
      $this->sessionVars['update']['user_nickname'] = $this->memberRecord['dataBaseRecord']['details']['nickName'];
      $this->sessionVars['update']['user_email'] = $this->memberRecord['dataBaseRecord']['details']['emailAddress'];
      $this->sessionVars['update']['user_address'] = $this->memberRecord['dataBaseRecord']['address']['address'];
      $this->sessionVars['update']['user_suburb'] = $this->memberRecord['dataBaseRecord']['address']['suburb'];
      $this->sessionVars['update']['user_state_id'] = $this->memberRecord['dataBaseRecord']['address']['state'];
      $this->sessionVars['update']['user_state'] = $this->memberRecord['dataBaseRecord']['address']['state'];
      $this->sessionVars['update']['user_postcode'] = $this->memberRecord['dataBaseRecord']['address']['postCode'];
      $this->sessionVars['update']['user_phone_home'] = $this->memberRecord['dataBaseRecord']['details']['phoneHome'];
      $this->sessionVars['update']['user_phone_work'] = $this->memberRecord['dataBaseRecord']['details']['phoneWork'];
      $this->sessionVars['update']['user_mobile'] = $this->memberRecord['dataBaseRecord']['details']['phoneMobile'];
      $this->sessionVars['update']['user_dob'] = $this->formatDateToForward($this->memberRecord['dataBaseRecord']['details']['dateOfBirth']);
      $this->sessionVars['update']['user_gender'] = $this->memberRecord['dataBaseRecord']['details']['gender'];
      $this->sessionVars['update']['receiveMarketingMaterial'] = $this->memberRecord['dataBaseRecord']['details']['receiveMarketingMaterial'];
      $this->sessionVars['update']['correspondenceType'] = $this->memberRecord['dataBaseRecord']['details']['correspondenceType'];
      $this->sessionVars['update']['preferedPaymentMethod'] = $this->memberRecord['dataBaseRecord']['details']['preferedPaymentMethod'];
      $this->sessionVars['update']['user_heardabout'] = $this->memberRecord['dataBaseRecord']['attributes'][4]['value'];
      $this->sessionVars['update']['user_donor'] = $this->memberRecord['dataBaseRecord']['details']['isOrganDonor'];
      $this->sessionVars['update']['user_donorFreeText'] = "";
      if($this->memberRecord['dataBaseRecord']['details']['donorEye'] == 't'){
        $this->sessionVars['update']['user_donorFreeText'] .= "Eye, ";
      }
      if($this->memberRecord['dataBaseRecord']['details']['donorHeart'] == 't'){
        $this->sessionVars['update']['user_donorFreeText'] .= "Heart, ";
      }
      if($this->memberRecord['dataBaseRecord']['details']['donorKidney'] == 't'){
        $this->sessionVars['update']['user_donorFreeText'] .= "Kidney, ";
      }
      if($this->memberRecord['dataBaseRecord']['details']['donorAny'] == 't'){
        $this->sessionVars['update']['user_donorFreeText'] .= "Any, ";
      }
      $this->sessionVars['update']['user_donorFreeText'] .= unclean($this->memberRecord['dataBaseRecord']['details']['donorFreeText']);
      $this->sessionVars['update']['check_newsletter'] = $this->memberRecord['dataBaseRecord']['details']['receiveMarketingMaterial'];
      
      $this->sessionVars['em']['emergencyInfo'] = unclean($this->memberRecord['dataBaseRecord']['details']['emergencyInformation']);
      $this->sessionVars['em']['contact_name'] = $this->memberRecord['dataBaseRecord']['emergencyContact']['name'];
      $this->sessionVars['em']['contact_relationship'] = $this->memberRecord['dataBaseRecord']['emergencyContact']['relationship'];
      $this->sessionVars['em']['contact_address'] = unclean($this->memberRecord['dataBaseRecord']['emergencyContact']['address']);
      $this->sessionVars['em']['contact_suburb'] = unclean($this->memberRecord['dataBaseRecord']['emergencyContact']['suburb']);
      $this->sessionVars['em']['contact_postcode'] = unclean($this->memberRecord['dataBaseRecord']['emergencyContact']['postCode']);
      $this->sessionVars['em']['contact_state_id'] = unclean($this->memberRecord['dataBaseRecord']['emergencyContact']['state']);
      $this->sessionVars['em']['contact_country'] = unclean($this->memberRecord['dataBaseRecord']['emergencyContact']['country']);
      $this->sessionVars['em']['contact_phone_home'] = $this->memberRecord['dataBaseRecord']['emergencyContact']['phoneHome'];
      $this->sessionVars['em']['contact_phone_work'] = $this->memberRecord['dataBaseRecord']['emergencyContact']['phoneWork'];
      $this->sessionVars['em']['contact_mobile'] = $this->memberRecord['dataBaseRecord']['emergencyContact']['phoneMobile'];
      
      $this->sessionVars['doc']['name'] = unclean($this->memberRecord['dataBaseRecord']['doctor']['doctorName']);
      $this->sessionVars['doc']['medical_centre'] = unclean($this->memberRecord['dataBaseRecord']['doctor']['medicalCentreName']);
      $this->sessionVars['doc']['address'] = unclean($this->memberRecord['dataBaseRecord']['doctor']['address']);
      $this->sessionVars['doc']['suburb'] = unclean($this->memberRecord['dataBaseRecord']['doctor']['suburb']);
      $this->sessionVars['doc']['postcode'] = unclean($this->memberRecord['dataBaseRecord']['doctor']['postCode']);
      $this->sessionVars['doc']['state_id'] = unclean($this->memberRecord['dataBaseRecord']['doctor']['state']);
      $this->sessionVars['doc']['country'] = unclean($this->memberRecord['dataBaseRecord']['doctor']['country']);
      $this->sessionVars['doc']['phone'] = $this->memberRecord['dataBaseRecord']['doctor']['phoneNumber'];
      
      $this->sessionVars['medic']['conditions'] = $this->memberRecord['dataBaseRecord']['conditions'];
      $this->sessionVars['medic']['allergies'] = $this->memberRecord['dataBaseRecord']['allergies'];
      $this->sessionVars['medic']['medications'] = $this->memberRecord['dataBaseRecord']['medications'];
      $this->sessionVars['medic']['other'] = '';
      $this->sessionVars['medic']['records'] = '';
      $this->sessionVars['medic']['file_no'] = $this->memberRecord['dataBaseRecord']['doctor']['fileNumber'];
      $this->sessionVars['medic']['blood_group'] = $this->memberRecord['dataBaseRecord']['blood']['bloodGroup'];
      
      // $this->sessionVars['profileImage'] = $this->memberRecord['dataBaseRecord']['profileImage'];
      $this->sessionVars['attachments']['medicalRecordFiles'] = $this->memberRecord['dataBaseRecord']['attachments']['medicalRecordFiles'];
      $this->sessionVars['attachments']['otherFiles'] = $this->memberRecord['dataBaseRecord']['attachments']['otherFiles'];
      
      foreach($this->memberRecord['dataBaseRecord']['attributes'] as $at){
        $this->sessionVars['attributes']["{$at['id']}"] = $at['value'];
      }
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
	function buildUpdateMemberMedicAlertArray(){
	
		$check = false;
		// Website record
		//$this->memberRecord['webSiteRecord']['memberJoinDate']							= date('d M Y');
		//$this->memberRecord['webSiteRecord']['accountBalance'] 						= '0.00';
		//$this->memberRecord['webSiteRecord']['status']['statusTypeId'] 					= medicAlertApi::STATUS_ACTIVE_PENDING;
		//$this->memberRecord['webSiteRecord']['membership']['membershipTypeId']		= '6';
		//$this->memberRecord['webSiteRecord']['membership']['RenewalDate']				= '';
		
		$this->memberRecord['webSiteRecord']['pendingUpdate']							= '1';
		$this->memberRecord['webSiteRecord']['pendingUpdateDate']						= date('d/m/Y',time());
	
		$this->memberRecord['webSiteRecord']['details']['title']						= ucwords(strtolower($_SESSION['update']['user_title']));
		$this->memberRecord['webSiteRecord']['details']['firstName']					= ucwords(strtolower($_SESSION['update']['user_firstname']));
		$this->memberRecord['webSiteRecord']['details']['middleName']					= ucwords(strtolower($_SESSION['update']['user_middlename']));
		$this->memberRecord['webSiteRecord']['details']['surname']						= ucwords(strtolower($_SESSION['update']['user_lastname']));
		$this->memberRecord['webSiteRecord']['details']['nickName']						= ucwords(strtolower($_SESSION['update']['user_nickname']));
		$this->memberRecord['webSiteRecord']['details']['dateOfBirth']					= (htmlclean($_SESSION['update']['user_dob']));
		$this->memberRecord['webSiteRecord']['details']['gender']						= ucwords(strtolower($_SESSION['update']['user_gender']));
		$this->memberRecord['webSiteRecord']['details']['phoneHome']					= htmlclean($_SESSION['update']['user_phone_home']);
		$this->memberRecord['webSiteRecord']['details']['phoneWork']					= htmlclean($_SESSION['update']['user_phone_work']);
		$this->memberRecord['webSiteRecord']['details']['phoneMobile']					= htmlclean($_SESSION['update']['user_mobile']);
		$this->memberRecord['webSiteRecord']['details']['emailAddress']					= htmlclean($_SESSION['update']['user_email']);
		$this->memberRecord['webSiteRecord']['details']['emergencyInformation']			= ucwords(strtolower($_SESSION['em']['emergencyInfo']));
		$this->memberRecord['webSiteRecord']['details']['isOrganDonor']					= htmlclean($_SESSION['update']['user_donor']);
		$this->memberRecord['webSiteRecord']['details']['donorFreeText']				= sentence_case($_SESSION['update']['user_donorFreeText']);
		//$this->memberRecord['webSiteRecord']['details']['receiveMarketingMaterial']		= ($_SESSION['update']['check_newsletter']? '':'t');
		$this->memberRecord['webSiteRecord']['details']['correspondenceType']			= htmlclean($_SESSION['update']['correspondenceType']);
		//$this->memberRecord['webSiteRecord']['details']['preferedPaymentMethod']			= $_SESSION['update']['user_preferedPaymentMethod'];
		
		$this->memberRecord['webSiteRecord']['address']['address']						= ucwords(strtolower($_SESSION['update']['user_address']));
		$this->memberRecord['webSiteRecord']['address']['suburb']						= ucwords(strtolower($_SESSION['update']['user_suburb']));
		$this->memberRecord['webSiteRecord']['address']['postCode']						= htmlclean($_SESSION['update']['user_postcode']);
		$this->memberRecord['webSiteRecord']['address']['state']						= htmlclean($_SESSION['update']['user_state_id']);
		$this->memberRecord['webSiteRecord']['address']['country']						= 'Australia';
		
		$this->memberRecord['webSiteRecord']['blood']['bloodGroup']						= htmlclean($_SESSION['medic']['blood_group']);
		$this->memberRecord['webSiteRecord']['blood']['bloodType']						= htmlclean($_SESSION['medic']['blood_group']);
		$this->memberRecord['webSiteRecord']['blood']['otherInfo']						= htmlclean($_SESSION['medic']['blood_group']);
	
		$this->memberRecord['webSiteRecord']['emergencyContact']['relationship']		= ucwords(strtolower($_SESSION['em']['contact_relationship']));
		$this->memberRecord['webSiteRecord']['emergencyContact']['name']				= ucwords(strtolower($_SESSION['em']['contact_name']));
		$this->memberRecord['webSiteRecord']['emergencyContact']['address']				= ucwords(strtolower($_SESSION['em']['contact_address']));
		$this->memberRecord['webSiteRecord']['emergencyContact']['suburb']				= ucwords(strtolower($_SESSION['em']['contact_suburb']));
		$this->memberRecord['webSiteRecord']['emergencyContact']['postCode']			= htmlclean($_SESSION['em']['contact_postcode']);
		$this->memberRecord['webSiteRecord']['emergencyContact']['state']				= htmlclean($_SESSION['em']['contact_state_id']);
		$this->memberRecord['webSiteRecord']['emergencyContact']['country']				= 'Australia';
		$this->memberRecord['webSiteRecord']['emergencyContact']['phoneHome']			= htmlclean($_SESSION['em']['contact_phone_home']);
		$this->memberRecord['webSiteRecord']['emergencyContact']['phoneWork']			= htmlclean($_SESSION['em']['contact_phone_work']);
		$this->memberRecord['webSiteRecord']['emergencyContact']['phoneMobile']			= htmlclean($_SESSION['em']['contact_mobile']);
	
		$this->memberRecord['webSiteRecord']['doctor']['doctorName']					= ucwords(strtolower($_SESSION['doc']['name']));
		$this->memberRecord['webSiteRecord']['doctor']['medicalCentreName']				= ucwords(strtolower($_SESSION['doc']['medical_centre']));
		$this->memberRecord['webSiteRecord']['doctor']['address']						= ucwords(strtolower($_SESSION['doc']['address']));
		$this->memberRecord['webSiteRecord']['doctor']['suburb']						= ucwords(strtolower($_SESSION['doc']['suburb']));
		$this->memberRecord['webSiteRecord']['doctor']['postCode']						= htmlclean($_SESSION['doc']['postcode']);
		$this->memberRecord['webSiteRecord']['doctor']['state']							= htmlclean($_SESSION['doc']['state_id']);
		$this->memberRecord['webSiteRecord']['doctor']['country']						= 'Australia';
		$this->memberRecord['webSiteRecord']['doctor']['phoneNumber']					= htmlclean($_SESSION['doc']['phone']);
		$this->memberRecord['webSiteRecord']['doctor']['fileNumber']					= htmlclean($_SESSION['medic']['file_no']);

		$this->memberRecord['webSiteRecord']['conditions']								= htmlclean($_SESSION['medic']['conditions']);
		$this->memberRecord['webSiteRecord']['allergies']								= htmlclean($_SESSION['medic']['allergies']);
		$this->memberRecord['webSiteRecord']['medications']								= htmlclean($_SESSION['medic']['medications']);
	
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
		$this->memberRecord['webSiteRecord']['attributes'][0]['value']				= htmlclean($_SESSION['update']['dvagoldcard']);
		$this->memberRecord['webSiteRecord']['attributes'][1]['id']					= '12';
		$this->memberRecord['webSiteRecord']['attributes'][1]['text']				= 'Health Fund Name';
		$this->memberRecord['webSiteRecord']['attributes'][1]['value']				= htmlclean($_SESSION['update']['healthfundname']);
		$this->memberRecord['webSiteRecord']['attributes'][2]['id']					= '13';
		$this->memberRecord['webSiteRecord']['attributes'][2]['text']				= 'Health Fund Number';
		$this->memberRecord['webSiteRecord']['attributes'][2]['value']				= htmlclean($_SESSION['update']['healthfundnumber']);
		$this->memberRecord['webSiteRecord']['attributes'][3]['id']					= '14';
		$this->memberRecord['webSiteRecord']['attributes'][3]['text']				= 'Individual Health Identifier (eHealth)';
		$this->memberRecord['webSiteRecord']['attributes'][3]['value']				= htmlclean($_SESSION['update']['ehealth']);
	}
	
	
	/**
	 * Build and return the user array after login
	 * @param array $userData
	 * @param string $membershipNumber
	 * @return array 
	 */
	function getUserArray($userData, $userName){
		
		$user = array();
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
		$user['user_mobile']				= $userData['dataBaseRecord']['details']['phoneMobile'];
		
		//$user['user_status']				= $userData['dataBaseRecord']['status']['statusTypeId']; // Had to change this because we need to track pending status of renewal paid members
		$user['user_status']				= $userData['webSiteRecord']['status']['statusTypeId'];
		$user['user_memberJoinDate']		= $userData['dataBaseRecord']['memberJoinDate'];
		$user['user_accountBalance']		= $userData['dataBaseRecord']['accountBalance'];
		$user['user_membershipType']		= $userData['dataBaseRecord']['membership']['membershipTypeId'];
		$user['user_membershipTypeText']	= $this->getMembershipTypeText($userData['dataBaseRecord']['membership']['membershipTypeId']);
		$user['user_RenewalDate']			= date("Y-m-d", strtotime($userData['dataBaseRecord']['membership']['RenewalDate']));
		$user['user_RenewalMonth']			= date("F", strtotime($userData['dataBaseRecord']['membership']['RenewalDate']));
		$user['preferedPaymentMethod']	    = $userData['dataBaseRecord']['details']['preferedPaymentMethod'];
		
		$user['WestpacCustomerNo']     = $userData['dataBaseRecord']['WestpacCustomerNo'];
		
		$user['auto_billing_active']          = $userData['dataBaseRecord']['details']['autoBilling'];
		$user['auto_billing_method']          = $userData['dataBaseRecord']['details']['autoBillingMethod'];
		$user['auto_billing_register_date']   = $userData['dataBaseRecord']['details']['autoBillingAcceptDate'];
		
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
	
	
	function setPendingStatus(){
		try{
			$TMPmemberRecordJSONResponse = $this->medicAlertApi->memberRetrieve($this->token);
			$TMPmemberRecord = json_decode($TMPmemberRecordJSONResponse, true);
		}catch(exceptionMedicAlertApiNotAuthenticated $e){
			$_SESSION['user'] = null; unset($_SESSION['user']);
			header("Location: /Membership/Members_Area"); die();
		}catch(exceptionMedicAlertApiSessionExpired $e){
			$_SESSION['user'] = null; unset($_SESSION['user']);
			header("Location: /Membership/Members_Area"); die();
		}catch(exceptionMedicAlertApi $e){
			header("Location: /404"); die();
		}
		$TMPmemberRecord['webSiteRecord']['status']['statusTypeId'] = medicAlertApi::STATUS_ACTIVE_PENDING;
		$_SESSION['user']['user_status'] = medicAlertApi::STATUS_ACTIVE_PENDING;
		try{
		  if($TMPmemberRecord['dataBaseRecord']['memberShipNumber'] !== $_SESSION['user']['user_id']){
		    $this->medicAlertApi->logOut($this->token);
		    header("Location: /Membership/Members_Area"); die();
		  }else{
			  $memberUpdated = $this->medicAlertApi->memberUpdate($this->token, json_encode($TMPmemberRecord));
			  $this->refreshUserArray();
		  }
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
	
	
	function processUpdate(){
		try{
		  
		  $TMPmemberRecordJSONResponse = $this->medicAlertApi->memberRetrieve($this->token);
		  $TMPmemberRecord = json_decode($TMPmemberRecordJSONResponse, true);
		  if($TMPmemberRecord['dataBaseRecord']['memberShipNumber'] !== $_SESSION['user']['user_id']){
		    $this->medicAlertApi->logOut($this->token);
		    header("Location: /Membership/Members_Area"); die();
		  }else{
		  
  			$this->buildUpdateMemberMedicAlertArray();
  			
  			$t_arr = unclean($this->memberRecord);
  			$memberUpdated = $this->medicAlertApi->memberUpdate($this->token, json_encode($t_arr));
  			
  			if($_SESSION['update']['user_newpassword'] != ""){
  				$password_error = false;
  				// update the password (using the proper password)
  				try{
  					$results = $this->medicAlertApi->updatePassWord($this->token, $_SESSION['update']['user_currentpassword'], $_SESSION['update']['user_newpassword']);
  				}catch(exceptionMedicAlertNotFound $e){ // may be a different exception to catch, but this is what the example used.
  					// incorrect password
  					$password_error = true;
  					$_SESSION['Errors'] = '<ul><li>Incorrect password, password not updated.</li></ul>';
  				}
  				if(!$password_error){
  					// and log the user out
  					$this->medicAlertApi->logOut($this->token);
  					// log in using the new password
  					$authenticationRecord = json_decode($this->medicAlertApi->authenticate($_SESSION['user']['user_id'], $_SESSION['update']['user_newpassword']), true);
  					$this->token = $authenticationRecord['sessionToken'];
  					$memberRecord = json_decode($this->medicAlertApi->memberRetrieve($this->token), true);
  					$_SESSION['user'] = $this->getUserArray($memberRecord, $_REQUEST['username']);
  				}
  			}
  			
  			try{
  				$memberShipNumber = clean($_SESSION['user']['user_id']);
  				$state = clean($_SESSION['user']['user_state_id']);
  				$sql = "INSERT INTO tbl_userlog (userlog_action,userlog_memberno,userlog_state,userlog_ip) VALUES ('UPDATE','{$memberShipNumber}','{$state}','{$_SERVER['REMOTE_ADDR']}')";
  				wrappedSqlInsert($sql);
  			}catch(Exception $e){}
  			
  			try{
  				$sql = "INSERT INTO tbl_update (update_member_no, update_ip) VALUES ('{$_SESSION['user']['user_id']}', '{$_SERVER['REMOTE_ADDR']}')";
  				wrappedSqlInsert($sql);
  			}catch(Exception $e){}

  			/**** EMAIL GOES HERE TO INFORM ABOUT changes taking time ****/
  			$name = $this->memberRecord['webSiteRecord']['details']['firstName'];
  			/*$body = "<html>
  					<head>
  	 				<style>
  					body { font-family:calibri,Helvetica,sans-serif; font-size:13px; line-height:18px; }
  					table {width:600px;}
  					table th, table td { padding:3px; text-align:left;}
  					table th { text-align:left; background-color:#f3f3f3; line-height:25px;  }
  					table td { text-align:left; }
  					</style>
  					</head>
  					<body>
  					<table>
  					<tr align='left'>
  						<td>Hi {$name}</td>
  					</tr>
  					<tr align='left'>
  						<td>
  						Thank you for submitting the changes to your profile. You will receive a confirmation email once our Membership Services team has reviewed and updated your profile.  
  	<br><br>
  	This email is automatically generated. Please do not reply to this email. 
  	<br><br>
  	If you have any queries please call Membership Services on CALL 1800 88 22 22 (Monday to Friday, 9am - 5pm CST), or email enquiry@medicalert.org.au.
  	<br><br>					
  						</td>
  					</tr>
  					</table>
  					<table> <tr align='left'>
  			    	<td>Best regards<br><br>
  					Membership Services Team<br><br>
  	
  					MedicAlert Foundation<br>
  					Level 1,210 Greenhill Road<br>
  	                Eastwood SA 5063<br>
  	                CALL: 1800 88 22 22<br>
  	                FAX: 1800 64 32 59<br>
  					</td>
  			    	</tr></table>";*/
  			$body = mb_convert_encoding(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/email-templates/update-profile.html'),'HTML-ENTITIES','UTF-8');
  			if($body) {
  				$body = str_replace('[firstname]',$name,$body);
  			}
  			//$to = $_SESSION['checkout']['billing']['email'];
  			//$to= 'nijesh@them.com.au';
  			$to = $_SESSION["user"]["user_email"];
  			$subject = "Update Member Profile";
  			sendMail($to, 'MedicAlert Foundation', 'donotreply@medicalert.org.au', $subject, $body);
		  }
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
	 * Refreshes the user array
	 */
	function refreshUserArray(){
		try{
			$this->memberAuthJSONResponse = $this->medicAlertApi->memberRetrieve($this->token);
			if($this->memberAuthJSONResponse != ''){
				$user_data = json_decode($this->memberAuthJSONResponse, true);
				$_SESSION['user'] = $this->getUserArray($user_data, $_SESSION['user']['user_username']);
			}
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
	 * Formats the date of birth from DD-MM-YYYY to YYYY-MM-DD
	 * @param string $date
	 * @return string
	 */
	function formatDateToBackwards($date){
		
		$formattedDate = substr($date, 6, 4).'-';
		$formattedDate.= substr($date, 3, 2).'-';
		$formattedDate.= substr($date, 0, 2);
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
	 * Redirect member after update based on membership type
	 */
	function redirectUpdatedMember(){
  	$goMobile = '';
  	if($_POST['mobile-site'] == 1){
  	  $goMobile = '/mobile';
  	}
		
		if($this->isAnnualMember()){
				if(!$this->isPendingMember() && ($this->isUnfinancialMember() || $this->isPastRenewalMonth() >= 1)){
					// User is unfinacial, redirect to fees payment page
					header("Location:{$goMobile}/Membership/Fees_Payment");
					die();
				}elseif($this->isPendingMember()){
					// User already has updates pending, redirect to error page
					header("Location:{$goMobile}/Membership/View_Personal_Medical_Details");
					die();
				}else{
					//echo 'member not active, unfinancial, or pending'; 
					header("Location:{$goMobile}/Membership/View_Personal_Medical_Details");
					die();
				}

		}elseif($this->isLifetimeMember()){
				
				if(!isset($cart_obj)){
					$cart_obj = new cart();
				}
				$product_id = $_SESSION['lifetime_membership_update_pid'];
				$quantity = '1';
				$row = GetRow('tbl_product', 'product_id = "'.clean($product_id).'"');
				$price = $row['product_price'];
				$res = $cart_obj->AddToCart($product_id, $row['product_name'], $row['product_product_category_id'], $price, $quantity, 0, "", 0, 0, "", "", "");
				header("Location:{$goMobile}/Checkout");
				die();
		}else{
				// unsupported member type
				header("Location:{$goMobile}/Membership/View_Personal_Medical_Details");
				die();
		}

	}
	
	
	/**
	 * Determines if the member is a lifetime (pay as you go) member or not
	 * @return boolean
	 */
	function isLifetimeMember(){

		if(	$_SESSION['user']['user_membershipType'] == medicAlertApi::MEMBERSHIP_GRANDFATHER ||
			$_SESSION['user']['user_membershipType'] == medicAlertApi::MEMBERSHIP_BUSINESS ||
			$_SESSION['user']['user_membershipType'] == medicAlertApi::MEMBERSHIP_LIFETIME ||
			$_SESSION['user']['user_membershipType'] == medicAlertApi::MEMBERSHIP_BENOVOLENT ){
			return true;
		}else{
			return false;
		}
		
	}
	
	
	/**
	 * Determines if the member is an annual (annual renewal) member or not
	 * @return boolean
	 */
	function isAnnualMember(){
		
		if(	$_SESSION['user']['user_membershipType'] == medicAlertApi::MEMBERSHIP_CLICKOVER ||
			$_SESSION['user']['user_membershipType'] == medicAlertApi::MEMBERSHIP_ANNUAL ||
			$_SESSION['user']['user_membershipType'] == medicAlertApi::MEMBERSHIP_BENOVOLENT_ANNUAL ){
			return true;
		}else{
			return false;
		}

	}
	
	
	/**
	 * Determines if the member status is active or not
	 * @return boolean
	 */
	function isActiveMember(){
	
		if($this->memberRecord['dataBaseRecord']['status']['statusTypeId'] == medicAlertApi::STATUS_ACTIVE){
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	 * Determines if the member status is active or not
	 * @return boolean
	 */
	function isInactiveMember(){
	
		if($this->memberRecord['dataBaseRecord']['status']['statusTypeId'] == medicAlertApi::STATUS_UNCLAIMED
		|| $this->memberRecord['dataBaseRecord']['status']['statusTypeId'] == medicAlertApi::STATUS_NO_LONGER_REQUIRED
		|| $this->memberRecord['dataBaseRecord']['status']['statusTypeId'] == medicAlertApi::STATUS_DECEASED){
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	 * Determines if the member status is unfinancial or not
	 * @return boolean
	 */
	function isUnfinancialMember(){

		if($this->memberRecord['dataBaseRecord']['status']['statusTypeId'] == medicAlertApi::STATUS_UNFINANCIAL || $this->memberRecord['webSiteRecord']['status']['statusTypeId'] == medicAlertApi::STATUS_UNFINANCIAL ){
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
	function isPastRenewalMonth(){
		$renewal_year = date("Y", strtotime($this->memberRecord['dataBaseRecord']['membership']['RenewalDate']));
		$current_year = date("Y", time());
		$year_diff = ($current_year - $renewal_year) * 12; //Number of years difference times 12 months in a year to get difference in months
		$renewal_month = date("m", strtotime($this->memberRecord['dataBaseRecord']['membership']['RenewalDate']));
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
	function isPendingMember(){

		//if($_SESSION['user']['user_status'] == medicAlertApi::STATUS_ACTIVE_PENDING){ // from dataBaseRecord
		if($this->memberRecord['dataBaseRecord']['status']['statusTypeId'] == medicAlertApi::STATUS_ACTIVE_PENDING || $this->memberRecord['webSiteRecord']['status']['statusTypeId'] == medicAlertApi::STATUS_ACTIVE_PENDING){ // from webSiteRecord
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
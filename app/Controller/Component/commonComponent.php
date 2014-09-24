<?php
/**************************************************************************
 Coder  : Apurav
 Object : Component for common functions
**************************************************************************/ 
class commonComponent extends Component {
	var $components = array('Auth','Session');
	var $catArr 	= array();
	
	public $settings = array();
	/** Function to get time stamp in unix timestamp format **/
	function getTimeStamp() {
		return mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
	}  
	/** Function to get time stamp after years
	 @param int - number of year default is 1
	 return timestamp after some year  ***/
	function getTimeStampAfterYear($years=1) {
		return mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')+$years);
	} 
	/* Function to get time stamp after some time or days or month letter */
	function getTimeStampLaterDates($days=0,$months=0,$years=0) {
		return mktime(date('H'),date('i'),date('s'),date('m')+$months,date('d')+$days,date('Y')+$years);
	}   
	/*** Create a random string
	 * @param	int $length - length of the returned number
	 * @return	string - string ***/
	function randomString($length = 8)	{
		$pass = "";
		// possible password chars.
		$chars = array("a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J",
			   "k","K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T",
			   "u","U","v","V","w","W","x","X","y","Y","z","Z","1","2","3","4","5","6","7","8","9");
		for($i=0 ; $i < $length ; $i++) {
			$pass .= $chars[mt_rand(0, count($chars) -1)];
		}
		return $pass;
	}
	

	function getAdminSetting()  // get admin setting
	{
		if(count($this->settings) == 0){
			APP::import('Model','Setting');
			$this->Setting = new Setting();
			$this->settings = $this->Setting->find('first',array('condition'=>array('Setting.id'=>'1')));
		}
		return $this->settings;
	}	
	
	function isUserGroupAccesss($controller,$action,$userGroupId)
	{
			App::import("Model", "Usermgmt.UserGroup");
			$this->userGroupModel = new UserGroup;
			return $this->userGroupModel->isUserGroupAccess($controller, $action, $userGroupId);
	}
	function groupUser($userGroupId)
	{
			App::import("Model", "Usermgmt.User");
			$this->userModel = new User;
			return $this->userModel->find('count',array('conditions'=>array('user_group_id'=>$userGroupId)));
	}
	
	// get site settings 
	function getSettingas()  // get all project list
	{
		APP::import('Model','Setting');
		$this->Setting = new Setting();
		return $this->Setting->find('first');
	}
	

	function checkUserEmails($emailId=null)
		{
			APP::import('Model','User');
			$this->User = new User(); 
			$candidateRec = $this->User->find("count",array('conditions'=>array('user.email="'.$emailId.'"')));
			if(!$candidateRec)
			{
				return true;
			}
			return false;
		}
	

	function getUserDetail($userId)
	{
		App::import("Model", "Usermgmt.User");
		$this->userModel = new User;
		return $this->userModel->find('first',array('conditions'=>array('User.id'=>$userId)));
	}

		
	function __construct()
	{
	
	}
                
	function countReplyComment($commentID = null){
		App::import("Model", "Blog.BlogPostComment");
		$this->BlogPostComment = new BlogPostComment();
		return $this->BlogPostComment->find('count',array('conditions'=>array('comment_parent'=>$commentID,'published'=>1)));
	}
	
	
	
	   
   function getReplyComment($commentID){
		   App::import("Model", "Blog.BlogPostComment");
		   $this->BlogPostComment = new BlogPostComment;
		   return $this->BlogPostComment->find('threaded',array('order'=>'id desc','conditions'=>array('BlogPostComment.comment_parent'=>$commentID)));
   }
           
       
		   
	public function returnSettingValue($id = null){
		App::import("Model", "Blog.BlogSetting");
		$this->BlogSetting = new BlogSetting;
		$blogSettingVal = $this->BlogSetting->find('first',array('conditions'=>array('BlogSetting.id'=>$id)));
		return $blogSettingVal;
	}
	
	
	   
			//============ function to get language list ========================
	function getLanguageList()   
	{
		App::import("Model", "Language");
		$this->Language = new Language;		
	   return $this->Language->find('list',array('fields'=>'id,name'));
	}
	//============ End ==================================================
		
	//============ function to get language list ========================
	function getPageVariable($array,$language)   
	{
		App::import("Model", "DefinedLanguage");
		$this->definedLanguage = new DefinedLanguage;
		
		$currentLanguageVariables=array();
		foreach($array as $key=>$value)
		{
			$rec=$this->definedLanguage->find('first',array('fields'=>'DefinedLanguage.value',
															'conditions'=>'DefinedLanguageContent.content="'.$value.'" and 
																DefinedLanguage.language_id="'.$language.'"'
															));
			$currentLanguageVariables[$value]=$rec['DefinedLanguage']['value'];
		}	
		pr($currentLanguageVariables);die;
		return $currentLanguageVariables;	
	}
	//============ End ==================================================
	
	function getLanguageVarValue($variable,$language)
	{
		App::import("Model", "DefinedLanguageContent");
		$this->DefinedLanguageContent = new DefinedLanguageContent;
		$rec=$this->DefinedLanguageContent->find('first',array('fields'=>'DefinedLanguage.value',
															'conditions'=>'DefinedLanguageContent.content="'.$variable.'" and 
																DefinedLanguage.language_id="'.$language.'"'
															));
		
		if(!$rec['DefinedLanguage']['value'])
		{
			$rec=$this->DefinedLanguageContent->find('first',array('fields'=>'DefinedLanguage.value',
															'conditions'=>'DefinedLanguageContent.content="'.$variable.'" and 
																DefinedLanguage.language_id="1"'
															));
		}
		return $rec['DefinedLanguage']['value'];
	}
		
		
	function getLeadList()
	{
		App::import("Model", "Lead");
		$this->Lead = new Lead;
		return $this->Lead->find('list',array('fields'=>'Lead.id,Lead.full_name','recursive'=>-2));
	}
	
	function testing($id)
	{
		return $id.'<br><br>';
	}
	
	function get_campagin_keyowrd_key_list($campagin_id)
	{
		App::import("Model", "Keyword");
		$this->Keyword = new Keyword;
		$keywordList=$this->Keyword->find('list',array('conditions'=>'Keyword.campaign_id="'.$campagin_id.'"','fields'=>'Keyword.keyword'));
		return $keywordList;
	}
	
	function get_form_list(){
		App::import("Model", "Form");
		$this->Form = new Form;
		$form_list=$this->Form->find('list',array('fields'=>'Form.name'));
		return $form_list;
	}
	
	function get_campagin_keyowrd_value_list($campagin_id)
	{
		App::import("Model", "Keyword");
		$this->Keyword = new Keyword;
		
		$keywordList=$this->Keyword->find('list',array('conditions'=>'Keyword.campaign_id="'.$campagin_id.'"','fields'=>'Keyword.value'));
		return $keywordList;
	}	
	
	function get_campagin_list(){
		App::import("Model", "Campaign");
		$this->Campaign = new Campaign;
		$campaign_list=$this->Campaign->find('list',array('fields'=>'Campaign.name'));
		return $campaign_list;
	}
	
	function get_customer_campagin_list($customer_id){
		App::import("Model", "Campaign");
		$this->Campaign = new Campaign;
		$campaign_list=$this->Campaign->find('list',array('fields'=>'Campaign.name','conditions'=>'Campaign.customer_id="'.$customer_id.'"'));
		return $campaign_list;
	}
	
	function get_customer_list(){
		App::import("Model", "Customer");
		$this->Customer = new Customer;
		$customer_list=$this->Customer->find('list',array('fields'=>'Customer.name','order'=>'Customer.name'));
		return $customer_list;
	}

	
	function get_campagin_name($lead_id){
		App::import("Model", "Campaign");
		$this->Campaign = new Campaign;
		$this->Campaign->id=$lead_id;
		return $this->Campaign->field('name');
	}
	
	function get_customer_name($customer_id){
		App::import("Model", "Customer");
		$this->Customer = new Customer;
		$this->Customer->id=$customer_id;
		return $this->Customer->field('name');
	}	
	
	function groupUserList(){
		App::import("Model", "Usermgmt.User");
		$this->userModel = new User;
		return $this->userModel->find('list',array('fields'=>array('id','username'),'order'=>'id DESC'));
	}
	
	function teamList(){
		App::import("Model", "Team");
		$this->Team = new Team;
		return $this->Team->find('list',array('fields'=>array('id','name'),'order'=>'id asc'));
	}
	
	function agentList(){
		App::import("Model", "Usermgmt.User");
		$this->User = new User;
		return $this->User->find('list',array('fields'=>array('id','username'),'order'=>'id asc'));
	}
	
	function five9_api($arrayRecord)
	{
	
		$action=$arrayRecord['action'];
		
		switch($action){
			case  'createList':
				$list_name=$arrayRecord['record'];
				$soapUser = Five9_username;  //  username
				$soapPassword = Five9_password; // password
				
				$soap_options   = array( 'login' => $soapUser, 'password' => $soapPassword );
				$auth_details   = base64_encode($soapUser.":".$soapPassword);
		
		
		// for admin functions
				$client = new SoapClient("https://api.five9.com/wsadmin/AdminWebService?wsdl", $soap_options);
				$header = new SoapHeader("https://api.five9.com/wsadmin/AdminWebService/createList", "authentication", "Basic $auth_details");
				
				$client->__setSoapHeaders($header);
				
				try
				{
					$res = $client->createList(array('listName'=>$list_name));
					//$res = $client->getListsInfo(array('listNamePattern'=>'newlist'));
					//$res = $client->getListsInfo();
					
				}
				
				catch (Exception $e)
				{
					$last_req = $client->__getLastRequest();
					$last_res = $client->__getLastResponse();
				
					//echo "Error:<br><br>request - $last_req<br>res - " . $last_res;
					"Error: (" . $e->getCode() . ") " . $e->getMessage();
					//exit;
				}
		
				//var_dump($res);
				//echo "<pre>";
				
			//	$last_req = $client->__getLastRequest();
			//	$last_res = $client->__getLastResponse();
			//	return "Info: " .$last_req . "  " . $last_res;

					
				break;
			
			case  'addListsToCampaign':
				$list_name=$arrayRecord['record'];
				$soapUser = Five9_username;  //  username
				$soapPassword = Five9_password; // password
				
				$soap_options   = array( 'login' => $soapUser, 'password' => $soapPassword );
				$auth_details   = base64_encode($soapUser.":".$soapPassword);
		
		
		// for admin functions
				$client = new SoapClient("https://api.five9.com/wsadmin/AdminWebService?wsdl", $soap_options);
				$header = new SoapHeader("https://api.five9.com/wsadmin/AdminWebService/addListsToCampaign", "authentication", "Basic $auth_details");
				
				$client->__setSoapHeaders($header);
				
				try
				{
					$res = $client->addListsToCampaign(array('campaignName'=>$arrayRecord['campaignName'],'lists'=>array('campaignName'=>$arrayRecord['campaignName'],'dialingPriority'=>1,'dialingRatio'=>1,'listName'=>$arrayRecord['record'],'priority'=>1)));
					
				}
				
				catch (Exception $e)
				{
					$last_req = $client->__getLastRequest();
					$last_res = $client->__getLastResponse();

					"Error: (" . $e->getCode() . ") " . $e->getMessage();
				}
					
				break;
				
							
			case 'getListsInfo':
					
				$soapUser = Five9_username;  //  username
				$soapPassword = Five9_password; // password
				
				$soap_options   = array( 'login' => $soapUser, 'password' => $soapPassword );
				$auth_details   = base64_encode($soapUser.":".$soapPassword);
		
		
		// for admin functions
				$client = new SoapClient("https://api.five9.com/wsadmin/AdminWebService?wsdl", $soap_options);
				$header = new SoapHeader("https://api.five9.com/wsadmin/AdminWebService/getListsInfo", "authentication", "Basic $auth_details");
				
				$client->__setSoapHeaders($header);
				
				try
				{
				
					$res = $client->getListsInfo();
					
					$array_list=array();
					foreach($res as $array){
						
						foreach($array as $array1){
							
							$array_list[$array1->name]=$array1->name;
							
						}
					}
					
					return $array_list;
				}
				
				catch (Exception $e)
				{
					$last_req = $client->__getLastRequest();
					$last_res = $client->__getLastResponse();
				
					return "Error: (" . $e->getCode() . ") " . $e->getMessage();
					
				}

					break;
		
				
			default:
				break;
		}
	}
	
	function add_to_record($lead_list){
		//============= send record to five 9 ========================================
			
		$lead_list['Lead']['listname']='newlist';
							$soapUser =Five9_username;  //  username
							$soapPassword =Five9_password; // password
			
							$soap_options   = array( 'login' => $soapUser, 'password' => $soapPassword );
							$auth_details   = base64_encode($soapUser.":".$soapPassword);
							
							$client = new SoapClient("https://api.five9.com/wsadmin/AdminWebService?wsdl", $soap_options);
							$header = new SoapHeader("https://api.five9.com/wsadmin/AdminWebService/addRecordToList", "authentication", "Basic $auth_details");
							
							$client->__setSoapHeaders($header);
								try
								{
								
								$res = $client->addRecordToList(array('listName'=>$lead_list['Lead']['listname'], 'listUpdateSettings'=>array('allowDataCleanup'=>FALSE, 'reportEmail'=>'4003@dothejob.org','separator'=>',', 'skipHeaderLine'=>true, 'cleanListBeforeUpdate'=>FALSE, 'crmAddMode'=>'ADD_NEW', 'crmUpdateMode'=>'UPDATE_FIRST','listAddMode'=>'ADD_FIRST', 'fieldsMapping'=>
															array(
																	array(
																		'columnNumber'=>1,
																		'fieldName'=>'number1',
																		'key'=>true
																	),
																	array(
																		'columnNumber'=>2,
																		'fieldName'=>'number2',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>3,
																		'fieldName'=>'number3',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>4,
																		'fieldName'=>'first_name',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>5,
																		'fieldName'=>'last_name',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>6,
																		'fieldName'=>'company',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>7,
																		'fieldName'=>'street',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>8,
																		'fieldName'=>'city',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>9,
																		'fieldName'=>'state',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>10,
																		'fieldName'=>'zip',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>11,
																		'fieldName'=>'id',
																		'key'=>false
																	)
																)													
															 ),
										  'record'=>array('fields'=>array('9782341663','9782341664','9782341665',$lead_list['Lead']['first_name'],$lead_list['Lead']['last_name'],'','','','',$lead_list['Lead']['zip_code'],$lead_list['Lead']['id']))
										 
										
													 ));
															/* 'importData'=>array('values'=>array('item'=>array('9782341663','','',$arrayAction['record']['first_name'],'item'=>$arrayAction['record']['last_name'],'','','','',$arrayAction['record']['pin_code'],$arrayAction['record']['id'])),)
														));
															 */
									 
								return	 $result='Record has been saved sucessfully';
								
								}
								catch (Exception $e)
								{
									$last_req = $client->__getLastRequest();
									$last_res = $client->__getLastResponse();
								
									//echo "Error:<br><br>request - $last_req<br>res - " . $last_res;
								 //"Error: (" . $e->getCode() . ") " . $e->getMessage();
								
								return $result="Error: (" . $e->getCode() . ") " . $e->getMessage();
									
								
								}
						
						//================ End =================
	
	}

 
 /*
  * @Vishnu sharma
  * @Uses To get Campaign List from five9 server.
  * @On 23-05-2013
 */
 
function __GetAllCampaignList(){

   	$soapUser =Five9_username;  //  username
	$soapPassword =Five9_password; // password
	
	$soap_options   = array( 'login' => $soapUser, 'password' => $soapPassword );
	$auth_details   = base64_encode($soapUser.":".$soapPassword);
	
	$client = new SoapClient("https://api.five9.com/wsadmin/AdminWebService?wsdl", $soap_options);
	$header = new SoapHeader("https://api.five9.com/wsadmin/AdminWebService/getCampaigns", "authentication", "Basic $auth_details");

  $client->__setSoapHeaders($header);
	try
	{
	$res = $client->getCampaigns(array('campaignNamePattern'=>'.*','campaignNamePattern'=>'.*'));
	 
		$result='Record has been saved sucessfully';
		}
		catch (Exception $e)
		{
			$last_req = $client->__getLastRequest();
			$last_res = $client->__getLastResponse();
		
			//echo "Error:<br><br>request - $last_req<br>res - " . $last_res;
		 //"Error: (" . $e->getCode() . ") " . $e->getMessage();
		
		$result="Error: (" . $e->getCode() . ") " . $e->getMessage();
			
		
		}
	$campaignList = array();
	if(!empty($res->return)){
		foreach($res->return as $campaign){
			$campaignList[$campaign->name] = $campaign->name;
		}
	}
	
	return $campaignList;
  

}

function GetAllCampaignList(){

   	$soapUser =Five9_username;  //  username
	$soapPassword =Five9_password; // password
	
	$soap_options   = array( 'login' => $soapUser, 'password' => $soapPassword );
	$auth_details   = base64_encode($soapUser.":".$soapPassword);
	
	$client = new SoapClient("https://api.five9.com/wsadmin/AdminWebService?wsdl", $soap_options);
	$header = new SoapHeader("https://api.five9.com/wsadmin/AdminWebService/getCampaigns", "authentication", "Basic $auth_details");

  $client->__setSoapHeaders($header);
	try
	{
	$res = $client->getCampaigns(array('campaignNamePattern'=>'.*','campaignType'=>array('INBOUND','OUTBOUND','AUTODIAL')));
	 
		$result='Record has been saved sucessfully';
		}
		catch (Exception $e)
		{
			$last_req = $client->__getLastRequest();
			$last_res = $client->__getLastResponse();
		
			//echo "Error:<br><br>request - $last_req<br>res - " . $last_res;
		 //"Error: (" . $e->getCode() . ") " . $e->getMessage();
		
		$result="Error: (" . $e->getCode() . ") " . $e->getMessage();
			
		
		}
	$campaignList = array();
	if(!empty($res->return)){
		foreach($res->return as $campaign){
			$campaignList[$campaign->name] = $campaign->name;
		}
	}
	
	return $campaignList;
  

}

// vishnu Method is used to Eun report and get identifier
function __runReport($from_date,$to_date,$folder_name,$report_name,$campagin_name){
	$soapUser = Five9_username; //Five9_username;  //  username
	$soapPassword = Five9_password; // Five9_password; // password

	$soap_options   = array( 'login' => $soapUser, 'password' => $soapPassword );
	$auth_details   = base64_encode($soapUser.":".$soapPassword);
	
	$client = new SoapClient("https://api.five9.com/wsadmin/AdminWebService?wsdl", $soap_options);
	$header = new SoapHeader("https://api.five9.com/wsadmin/AdminWebService/runReport", "authentication", "Basic $auth_details");
	$res = '';
	$client->__setSoapHeaders($header);
	try
	{
	$res = $client->runReport(array(
			'folderName'=>$folder_name,
			'reportName'=>$report_name,
			'criteria'=>array(
				'time'=>array('start'=>$from_date,'end'=>$to_date)
							)
				));
	$result='Record has been saved sucessfully';
	
	}catch (Exception $e){
		$last_req = $client->__getLastRequest();
		$last_res = $client->__getLastResponse();
		$result="Error: (" . $e->getCode() . ") " . $e->getMessage();
	 }
	$return = ($res=='')?$result:$res->return;
	return $return;
	}

// vishnu Method is used to Eun report and get identifier
function __isReportRunning($identifier = ''){
	$soapUser = Five9_username; //Five9_username;  //  username
	$soapPassword = Five9_password; // Five9_password; // password

	$soap_options   = array( 'login' => $soapUser, 'password' => $soapPassword );
	$auth_details   = base64_encode($soapUser.":".$soapPassword);
	
	$client = new SoapClient("https://api.five9.com/wsadmin/AdminWebService?wsdl", $soap_options);
	$header = new SoapHeader("https://api.five9.com/wsadmin/AdminWebService/isReportRunning", "authentication", "Basic $auth_details");
	$res = '';
	$client->__setSoapHeaders($header);
	try
	{
	$res = $client->isReportRunning(array('identifier'=>$identifier));
	$result='Record has been saved sucessfully';
	
	}catch (Exception $e){
		$last_req = $client->__getLastRequest();
		$last_res = $client->__getLastResponse();
		$result="Error: (" . $e->getCode() . ") " . $e->getMessage();
	 }
	
	$return = ($res=='')?$result:$res;
	return $return;
}

// vishnu Method is used to Eun report and get identifier
function __getReportResult($identifier = ''){
	$soapUser = Five9_username; //Five9_username;  //  username
	$soapPassword = Five9_password; // Five9_password; // password

	$soap_options   = array( 'login' => $soapUser, 'password' => $soapPassword );
	$auth_details   = base64_encode($soapUser.":".$soapPassword);
	
	$client = new SoapClient("https://api.five9.com/wsadmin/AdminWebService?wsdl", $soap_options);
	$header = new SoapHeader("https://api.five9.com/wsadmin/AdminWebService/getReportResult", "authentication", "Basic $auth_details");
	$res = '';
	$client->__setSoapHeaders($header);
	try
	{
	$res = $client->getReportResult(array('identifier'=>$identifier));
	$result='Record has been saved sucessfully';
	
	}catch (Exception $e){
		$last_req = $client->__getLastRequest();
		$last_res = $client->__getLastResponse();
		$result="Error: (" . $e->getCode() . ") " . $e->getMessage();
	 }
	$return = ($res=='')?$result:$res;
	return $return;

	}

function _getAgentName(){
	
}

function upselllist($campaginId,$value)
	{
		App::import("Model", "CampaignUpsale");
		$this->CampaignUpsale = new CampaignUpsale;
		return $this->CampaignUpsale->find('list',array('fields'=>'CampaignUpsale.upsell_id,CampaignUpsale.upsell_description','recursive'=>-2,'conditions'=>'CampaignUpsale.upsale_drop_down="'.$value.'"  and campaign_id="'.$campaginId.'" '));
	}


	
function _getCampaginProduct($campaginId)
	{
		App::import("Model", "CampaignProduct");
		$this->CampaignProduct = new CampaignProduct;
		return $this->CampaignProduct->find('list',array('fields'=>'CampaignProduct.id,CampaignProduct.prodct_description',
														  'conditions'=>'campaign_id="'.$campaginId.'"'
														));
	}
	
function _getpaymentGatwayList()
	{
		App::import("Model", "PaymentgatwayList");
		$this->PaymentgatwayList = new PaymentgatwayList;
		return $this->PaymentgatwayList->find('list',array('fields'=>'PaymentgatwayList.id,PaymentgatwayList.name'));
	}
	
function _getCampaginUpsellDropDown($campaginId)
	{
		App::import("Model", "CampaignUpsale");
		$this->CampaignUpsale = new CampaignUpsale;
		return $this->CampaignUpsale->find('list',array('fields'=>'CampaignUpsale.id,CampaignUpsale.upsale_drop_down','recursive'=>-2,'conditions'=>'campaign_id="'.$campaginId.'"','group'=>'upsale_drop_down'));
	}	
	
function _getDispositionList()
	{
		App::import("Model", "Disposition");
		$this->Disposition = new Disposition;
		return $this->Disposition->find('list',array('fields'=>'Disposition.five9_id,Disposition.name'));
	}	

function _updateLead($data)
	{
							$soapUser =Five9_username;  //  username
							$soapPassword =Five9_password; // password
			
							$soap_options   = array( 'login' => $soapUser, 'password' => $soapPassword );
							$auth_details   = base64_encode($soapUser.":".$soapPassword);
							
							$client = new SoapClient("https://api.five9.com/wsadmin/AdminWebService?wsdl", $soap_options);
							$header = new SoapHeader("https://api.five9.com/wsadmin/AdminWebService/addRecordToList", "authentication", "Basic $auth_details");
							
							$client->__setSoapHeaders($header);
								try
								{
								
								$res = $client->addRecordToList(array('listName'=>$data['Lead']['listname'], 'listUpdateSettings'=>array('allowDataCleanup'=>FALSE, 'reportEmail'=>'4003@dothejob.org','separator'=>',', 'skipHeaderLine'=>true, 'cleanListBeforeUpdate'=>FALSE, 'crmAddMode'=>'ADD_NEW', 'crmUpdateMode'=>'UPDATE_FIRST','listAddMode'=>'ADD_FIRST', 'fieldsMapping'=>
															array(
																	array(
																		'columnNumber'=>1,
																		'fieldName'=>$data['Lead']['phone'],
																		'key'=>true
																	),
																	array(
																		'columnNumber'=>2,
																		'fieldName'=>$data['Lead']['id'],
																		'key'=>false
																	)
																)													
															 ),
										  'record'=>array('fields'=>array($data['Lead']['phone'],$data['Lead']['id']))
													 ));
									
									 $result='Record has been saved sucessfully';
									 
								}
								catch (Exception $e)
								{
								
									$last_req = $client->__getLastRequest();
									$last_res = $client->__getLastResponse();
									$result="Error: (" . $e->getCode() . ") " . $e->getMessage();
									//echo "Error:<br><br>request - $last_req<br>res - " . $last_res;
									//"Error: (" . $e->getCode() . ") " . $e->getMessage();
								}
	}
//-----------------------------------------------------//
function saveCompgnList($listname,$campaignName) {
	APP::import('Model','Campaign');
	$this->Campaign = new Campaign();
	APP::import('Model','CampaignList');
	$this->CampaignList = new CampaignList();
	$Campaign = $this->Campaign->find('first',array('fields'=>'Campaign.id','conditions'=>array('Campaign.five9_campaign_name'=>$campaignName)));
	if(isset($Campaign['Campaign']['id'])) {
		$save = array();
		$save['CampaignList']['campaign_id'] = $Campaign['Campaign']['id'];
		$save['CampaignList']['list_name'] = $listname;
		$this->CampaignList->save($save,false);
	}
}
//-------------- Get credit card type from card number ---------------------------------------//
function creditCardType($ccNum)
 {
        /*
            * mastercard: Must have a prefix of 51 to 55, and must be 16 digits in length.
            * Visa: Must have a prefix of 4, and must be either 13 or 16 digits in length.
            * American Express: Must have a prefix of 34 or 37, and must be 15 digits in length.
            * Discover: Must have a prefix of 6011, and must be 16 digits in length.
        */
        if (preg_match("/^5[1-5][0-9]{14}$/", $ccNum))
                return "MASTERCARD";
 
        if (preg_match("/^4[0-9]{12}([0-9]{3})?$/", $ccNum))
                return "VISA";
 
        if (preg_match("/^3[47][0-9]{13}$/", $ccNum))
                return "AMEX";

        if (preg_match("/^6011[0-9]{12}$/", $ccNum))
                return "DISCOVER";
 }
}//Class end

?>

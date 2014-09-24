<?php
/* -----------------------------------------------------------------------------------------
   Call centre project - http://planetwebsolution.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2013 planetwebsolution Ltd.
   ---------------------------------------------------------------------------------------*/  
class LeadsController extends AppController {
	public $name = 'Lead';
    public $theme = 'Admin';
	
	public $uses = array('Customer','Campaign','Keyword','CallScript','Rebuttal','Lead','Support');
    public $helpers = array('Js','Html','Javascript','Text','Paginator','Ajax');     
	
	
	public function add_lead(){
		$success = __('Lead has been added successfully');
		$campaign_list=array();
		$this->set(compact('campaign_list'));
				
		$customer_list=$this->common->get_customer_list();   //==== get customer list
		$this->set(compact('customer_list'));
		
		
		
		if ($this->request->isPost()) {
			$this->Lead->set($this->request->data);
			if(!$this->Lead->lead_validation()){
				$campaign_list=$this->common->get_customer_campagin_list($this->request->data['Lead']['customer_id']);   //==== get campagin list
				$this->set(compact('campaign_list'));
				
			}else{
				if($this->Lead->save($this->request->data)){
				
				$data=$this->request->data;
				$data['Lead']['id']=$this->Lead->getLastInsertId();
				$data1['Lead']['id']=$data['Lead']['id'];
				
				
				//campaign_id
				$listname='newlist';
				$campaign_id = $data['Lead']['campaign_id'];
				   $findlist = $this->Campaign->find('first',array('fields'=>'Campaign.campaign_list_name','conditions'=>array('Campaign.id'=>$campaign_id)));
				   $listname = 'newlist';
				   if(isset($findlist['Campaign']['campaign_list_name'])) {
					$listname = $findlist['Campaign']['campaign_list_name'];
				   }
				
					$data['Lead']['listname']=$listname;
				
				
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
																		'fieldName'=>'Address1',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>12,
																		'fieldName'=>'Country',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>13,
																		'fieldName'=>'Email Address',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>14,
																		'fieldName'=>'id',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>15,
																		'fieldName'=>'Channel',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>16,
																		'fieldName'=>'Age',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>17,
																		'fieldName'=>'Donation Amount',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>18,
																		'fieldName'=>'Gender',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>19,
																		'fieldName'=>'Annual Income',
																		'key'=>false
																	)
																)													
															 ),
										  'record'=>array('fields'=>array($data['Lead']['phone'],'9782341664','9782341665',$data['Lead']['first_name'],$data['Lead']['last_name'],'','','','',$data['Lead']['zip_code'],$data['Lead']['address'],'US',$data['Lead']['email'],$data['Lead']['id'],$data['Lead']['channel'],$data['Lead']['age'],$data['Lead']['donation_amount'],$data['Lead']['gender'],$data['Lead']['annual_income']))
										 
										
													 ));
												
									
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
				
				
				
			
				if($result=='Record has been saved sucessfully'){
					$data1['Lead']['five9_status']=1;
					$this->Lead->save($data1);
					
				}
				
				
				
				
				$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');	
					$this->redirect(array('controller'=>'leads','action'=>'leadslist'));
				//	$this->common->add_to_record($this->request->data);
					
					
				}
			}
		}
		
		
	}
	
	public function add_leads_five9(){
		$success = __('Lead has been added successfully');
		
		
		if ($this->request->isPost()) {
			if(empty($this->request->data['Lead']['campaign'])){
				echo 'please select campaign';die;
			}else if(empty($this->request->data['Lead']['name'])){
				echo 'please Enter name';die;
			}else
			{
					
					
					$arrayAction=array();
					$arrayAction['action']='createList';
					$arrayAction['record']=$this->request->data['Lead']['name'];
					$resullLeadname=$this->common->five9_api($arrayAction);
					
					if(strpos($resullLeadname,'already exists.')){
						$this->autoRender=false;
						echo $resullLeadname;die;
					}else{
						$arrayAction=array();
						$arrayAction['action']='addListsToCampaign';
						$arrayAction['record']=$this->request->data['Lead']['name'];
						$arrayAction['campaignName']=$this->request->data['Lead']['campaign'];
						$addlist2Compaign=$this->common->five9_api($arrayAction);
						$this->common->saveCompgnList($this->request->data['Lead']['name'],$this->request->data['Lead']['campaign']);
						$this->autoRender=false;
						echo 'List has been added successfully';die;
					}
			}
		}
		$five9Campaigns=$this->common->GetAllCampaignList();
		$this->set('five9Campaigns',$five9Campaigns);
	}
	
	public function edit_lead($leadId = null ){
		$success = __('Lead has been edit successfully');
		
		$campaign_list=array();
		$this->set(compact('campaign_list'));
				
		$customer_list=$this->common->get_customer_list();   //==== get customer list
		$this->set(compact('customer_list'));

		
		if ($this->request->isPost()) {
			$this->Lead->set($this->request->data);
			
			if(!$this->Lead->lead_validation()){
				
				$campaign_list=$this->common->get_customer_campagin_list($this->request->data['Lead']['customer_id']);   //==== get campagin list
				$this->set(compact('campaign_list'));
				
			}else{
				
				if($this->Lead->save()){
					$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');	
					$this->redirect(array('controller'=>'leads','action'=>'leadslist'));
				}
			}
		}else
		{
			$this->request->data = $this->Lead->findById($leadId);
			
			$campaign_list=$this->common->get_customer_campagin_list($this->request->data['Lead']['customer_id']);   //==== get campagin list
			$this->set(compact('campaign_list'));
		}
		
			
	}
	
	function import_lead_to_five9(){
		
		$arrayAction['action']='getListsInfo';    // get five9 lead list
		$arrayAction['record']='Developerssssssss';
		$five9List=$this->common->five9_api($arrayAction);
		
		$this->set('five9List',$five9List);
	
		// get project lead list
		$this->paginate	= array('order'=>array('Lead.id desc'),'recursive'=>-1,'limit'=>PAGE_RECORD,'conditions'=>'Lead.five9_status=0');
		$leadList=$this->paginate('Lead');
		$this->set(compact('leadList'));
		
		
		if($this->request->is('post'))
		{
				
				if(empty($this->request->data['Lead']['lead_list'])){
					 $this->Lead->validationErrors['lead_list'] = "Please select list.";
				}
				
				if(!$this->Lead->validationErrors){
				
				$rec=$this->request->data['Lead']['lead_id'];
				$i=1;
				foreach($rec as $key=>$value){
				
					if($value['published']){
						$this->Lead->recursive=-1;
						$LeadRec=$this->Lead->findById($value['published']);					
						$arrayAction['action']='addToList';
						$arrayAction['record']=$LeadRec['Lead'];
						$arrayAction['record']['name']=$this->request->data['Lead']['lead_list'];
						
						//=======================================================================
						
						
						//=================	Change status ============
							$lead_list['Lead']['id']=$value['published'];
							$lead_list['Lead']['five9_status']=1;
							
						$this->Lead->save($lead_list);
							$lead_list['Lead']['id']='';
						//========================================================================
						
						
						
							//============= send record to five 9 ========================================
							$soapUser =Five9_username;  //  username
							$soapPassword =Five9_password; // password
			
							$soap_options   = array( 'login' => $soapUser, 'password' => $soapPassword );
							$auth_details   = base64_encode($soapUser.":".$soapPassword);
							
							$client = new SoapClient("https://api.five9.com/wsadmin/AdminWebService?wsdl", $soap_options);
							$header = new SoapHeader("https://api.five9.com/wsadmin/AdminWebService/addRecordToList", "authentication", "Basic $auth_details");
							
							$client->__setSoapHeaders($header);
								try
								{
								
								$res = $client->addRecordToList(array('listName'=>$this->request->data['Lead']['lead_list'], 'listUpdateSettings'=>array('allowDataCleanup'=>FALSE, 'reportEmail'=>'4003@dothejob.org','separator'=>',', 'skipHeaderLine'=>true, 'cleanListBeforeUpdate'=>FALSE, 'crmAddMode'=>'ADD_NEW', 'crmUpdateMode'=>'UPDATE_FIRST','listAddMode'=>'ADD_FIRST', 'fieldsMapping'=>
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
																		'fieldName'=>'Address1',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>12,
																		'fieldName'=>'Country',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>13,
																		'fieldName'=>'Email Address',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>14,
																		'fieldName'=>'id',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>15,
																		'fieldName'=>'Channel',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>16,
																		'fieldName'=>'Age',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>17,
																		'fieldName'=>'Donation Amount',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>18,
																		'fieldName'=>'Gender',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>19,
																		'fieldName'=>'Annual Income',
																		'key'=>false
																	)
																)													
															 ),
										  'record'=>array('fields'=>array($arrayAction['record']['phone'],'9782341664','9782341665',$arrayAction['record']['first_name'],$arrayAction['record']['last_name'],'','','','',$arrayAction['record']['zip_code'],$arrayAction['record']['address'],$arrayAction['record']['country'],$arrayAction['record']['email'],$arrayAction['record']['id'],$data['record']['channel'],$data['record']['age'],$data['record']['donation_amount'],$data['record']['gender'],$data['record']['annual_income']))
										 
										
													 ));
															/* 'importData'=>array('values'=>array('item'=>array('9782341663','','',$arrayAction['record']['first_name'],'item'=>$arrayAction['record']['last_name'],'','','','',$arrayAction['record']['pin_code'],$arrayAction['record']['id'])),)
														));
															 */
									 
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
					
								

						//================ End =================
						
						
						
						
						//$result=$this->common->five9_api($arrayAction);
					}
					$i=$i+1;
				}
					
						
			
				
				
					$this->Session->setFlash($result, 'Dialogs/top_right', array('type'=>'success'), 'top_right');	
					$this->redirect(array('controller'=>'leads','action'=>'import_lead_to_five9'));
					
				}else
				{
					$this->Lead->validationErrors;
				}
				
				
		
		
		
		
		}
	}
	
	
	
	public function leadslist(){
		
		
		$this->paginate	= array('order'=>array('Lead.id desc'),'limit'=>PAGE_RECORD,'recursive'=>-1,'limit'=>PAGE_RECORD);
		$leadList=$this->paginate('Lead');
		$this->set(compact('leadList'));
		
		
		$this->set('totalRecords', $this->Lead->find('count',array('recursive'=>-1)));	
	}
	
	public function view_lead($lead_id=null){
		$data=$this->Lead->Find('first',array('conditions'=>array('Lead.id'=>$lead_id)));
		$this->set(compact('data'));
	
	//$lead_id
	
	
	}
	
	public function viewsupport($lead_id=null){
		
		$data=$this->Support->Find('all',array('conditions'=>array('Support.lead_id'=>$lead_id)));
		$this->set(compact('data'));
	
	//$lead_id
	
	}
	
	public function viewrecording($lead_id=null){
		
		$this->loadmodel('CallData');
		$data=$this->CallData->Find('all',array('conditions'=>array('CallData.lead_id'=>$lead_id)));
		$this->set(compact('data'));
	
	//$lead_id
	
	}
	
	
	public function get_customer_campagin_list($customer_id=null)
	{
		$this->autoRender=false;
		$list=$this->common->get_customer_campagin_list($customer_id);
		$options='';
	$options.='<option value="'.$key.'">Please select campagin</option>'; 
		foreach($list as $key=>$value)
		{
			 $options.='<option value="'.$key.'">'.$value.'</option>'; 
		}
		
		echo $options;
	}
	
	public function delete_lead($leadId){
		$success = __('Lead is deleted successfully');
		$error = __('You cannot delete this lead');
		
		if(isset($leadId)){
			$this->Lead->delete($leadId,false);
			$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');	
			$this->redirect(array('controller'=>'leads','action'=>'leadslist'));
		}
	}
	
	
	public function  send_list_to_five9_cronjob(){
			$this->autoRender=false;
			
			
			$lead_rec=$this->Lead->find('all',array('conditions'=>'Lead.five9_status=0','recursive'=>-1));
			$five9_array=array();
			
		
			$i=1;
			foreach($lead_rec as $key=>$value){
					
					//$five9_array['item']=array($value['Lead']['phone'],'','',$value['Lead']['first_name'],'item'=>$value['Lead']['last_name'],'','','','',$value['Lead']['pin_code'],$value['Lead']['id']);
					
					$leadRec['Lead']['id']=$value['Lead']['id'];
					$leadRec['Lead']['five9_status']=1;
					$this->Lead->save($leadRec);
					
					//============= send record to five 9 ========================================
						
						//============= send record to five 9 ========================================
							$soapUser =Five9_username;  //  username
							$soapPassword =Five9_password; // password
			
							$soap_options   = array( 'login' => $soapUser, 'password' => $soapPassword );
							$auth_details   = base64_encode($soapUser.":".$soapPassword);
							
							$client = new SoapClient("https://api.five9.com/wsadmin/AdminWebService?wsdl", $soap_options);
							$header = new SoapHeader("https://api.five9.com/wsadmin/AdminWebService/addRecordToList", "authentication", "Basic $auth_details");
							
							$client->__setSoapHeaders($header);
								try
								{
								
								$res = $client->addRecordToList(array('listName'=>'newlist', 'listUpdateSettings'=>array('allowDataCleanup'=>FALSE, 'reportEmail'=>'4003@dothejob.org','separator'=>',', 'skipHeaderLine'=>true, 'cleanListBeforeUpdate'=>FALSE, 'crmAddMode'=>'ADD_NEW', 'crmUpdateMode'=>'UPDATE_FIRST','listAddMode'=>'ADD_FIRST', 'fieldsMapping'=>
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
										  'record'=>array('fields'=>array($value['Lead']['phone'],'9782341664','9782341665',$value['Lead']['first_name'],$value['Lead']['last_name'],'','','','',$value['Lead']['zip_code'],$value['Lead']['id']))
										 
										
													 ));
															/* 'importData'=>array('values'=>array('item'=>array('9782341663','','',$arrayAction['record']['first_name'],'item'=>$arrayAction['record']['last_name'],'','','','',$arrayAction['record']['pin_code'],$arrayAction['record']['id'])),)
														));
															 */
									 
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
						
						//================ End =================
			
			
				$i=$i+1;
			}
			
			
			
			
			
			
		}

/* Vishnu sharma. Uese to get disposition*/
 public function get_dispositionfinish(){
   
 	$soapUser =Five9_username;  //  username
	$soapPassword =Five9_password; // password

	//$soapUser = "rusty@seobrand.net";  //  username
	//$soapPassword = "rusty@seobrand.net"; // password
	
	$soap_options   = array( 'login' => $soapUser, 'password' => $soapPassword );
	$auth_details   = base64_encode($soapUser.":".$soapPassword);
	
	
	$client = new SoapClient("https://api.five9.com/wsadmin/AdminWebService?wsdl", $soap_options);
	$header = new SoapHeader("https://api.five9.com/wsadmin/AdminWebService/getDispositionsFromCampaign", "authentication", "Basic $auth_details");



	//$client = new SoapClient("http://localhost:8080/AgentBridge?wsdl", $soap_options);
	//$header = new SoapHeader("http://localhost:8080/AgentBridge/getContactRecords", "authentication", "Basic $auth_details");

$res = '';
	$client->__setSoapHeaders($header);
	try
	{
	$res = $client->getDispositionsFromCampaign(array('campaignName'=>'CoffeePMA'));
	
	//$res = $client->addDispositionsToCampaign(array('campaignName'=>'CoffeePMA','dispositions'=>'01 Core - Sure Fit','isSkipPreviewDisposition'=>false));
	
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
	pr($res);
	pr($result);
	die;
		

//================ End =================
   
   
  }
/* Upload lists direct from csv file */  
function import_csv() {

if($this->request->is('post'))
{
	if($this->request->data['Lead']['lead_list']=='') {
		$this->Session->setFlash('Please select a list.', 'Dialogs/top_right', array('type'=>'error'), 'top_right');
	} else if(($this->request->data['Lead']['csv']['type']!='application/csv' && $this->request->data['Lead']['csv']['type']!='application/vnd.ms-excel') || $this->request->data['Lead']['csv']['error']!=0) {
		$this->Session->setFlash('Please supply a valid csv file.', 'Dialogs/top_right', array('type'=>'error'), 'top_right');
	} else {
		if(($this->request->data['Lead']['csv']['type']=='application/csv' || $this->request->data['Lead']['csv']['type']=='application/vnd.ms-excel') && $this->request->data['Lead']['csv']['error']==0) {
		set_time_limit(0);
		$time=time();
		$name =WWW_ROOT.'csv/'.$time.'_'.$this->request->data['Lead']['csv']['name'];
		move_uploaded_file($this->request->data['Lead']['csv']['tmp_name'],$name);
		 
		//------------------------//
		$row = 1;
		$csvarr = array();
		$idsarr = array();
		$csvarr[] = 'number1,first_name,last_name,street,city,state,zip,id';
		if (($handle = fopen($name , "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000000, ",")) !== FALSE) {
				$num = count($data);
				if($row!=1) {
					if($data[2]!='') {
						
						$save = array();
						$save['Lead']['id'] = '';
						$save['Lead']['first_name'] = $data[0];
						$save['Lead']['last_name'] =  $data[1];
						$save['Lead']['phone'] =  $data[2];
						$save['Lead']['address'] =  $data[3];
						$save['Lead']['city'] =  $data[4];
						$save['Lead']['state'] =  $data[5];
						$save['Lead']['zip_code'] =  $data[6];
						
						$save['Lead']['channel'] =  $data[7];
						$save['Lead']['age'] =  $data[8];
						$save['Lead']['donation_amount'] =  $data[9];
						$save['Lead']['gender'] =  $data[10];
						$save['Lead']['annual_income'] =  $data[11];
						
						
						
						$save['Lead']['five9_status'] = 0;
						$this->Lead->save($save,false);
						$id = $this->Lead->getLastInsertId();
						$idsarr[] = $id;
						$csvarr[] = $data[2].','.$data[0].','.$data[1].','.$data[3].','.$data[4].','.$data[5].','.$data[6].','.$id.','.$data[7].','.$data[8].','.$data[9].','.$data[10].','.$data[11];
					}
				}
				$row++;
			}
			fclose($handle);
		}
		//-----------------//
		unlink(WWW_ROOT.'csv/'.$time.'_'.$this->request->data['Lead']['csv']['name']);
		
		$csvdata = implode("\n",$csvarr);
		/*echo $csvdata;
		exit;*/
		
							//============= send record to five 9 ========================================
							$soapUser =Five9_username;  //  username
							$soapPassword =Five9_password; // password
			
							$soap_options   = array( 'login' => $soapUser, 'password' => $soapPassword );
							$auth_details   = base64_encode($soapUser.":".$soapPassword);
							
							$client = new SoapClient("https://api.five9.com/wsadmin/AdminWebService?wsdl", $soap_options);
							$header = new SoapHeader("https://api.five9.com/wsadmin/AdminWebService/addToListCsv", "authentication", "Basic $auth_details");
							$client->__setSoapHeaders($header);
								try
								{
								$res = $client->addToListCsv(array('listName'=>$this->request->data['Lead']['lead_list'], 'listUpdateSettings'=>array('allowDataCleanup'=>FALSE, 'reportEmail'=>'4003@dothejob.org','separator'=>',', 'skipHeaderLine'=>true, 'cleanListBeforeUpdate'=>FALSE, 'crmAddMode'=>'ADD_NEW', 'crmUpdateMode'=>'UPDATE_FIRST','listAddMode'=>'ADD_FIRST', 'fieldsMapping'=>
															array(
																	array(
																		'columnNumber'=>1,
																		'fieldName'=>'number1',
																		'key'=>true
																	),
																	array(
																		'columnNumber'=>2,
																		'fieldName'=>'first_name',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>3,
																		'fieldName'=>'last_name',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>4,
																		'fieldName'=>'street',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>5,
																		'fieldName'=>'city',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>6,
																		'fieldName'=>'state',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>7,
																		'fieldName'=>'zip',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>8,
																		'fieldName'=>'id',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>9,
																		'fieldName'=>'Channel',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>10,
																		'fieldName'=>'Age',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>11,
																		'fieldName'=>'Donation Amount',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>12,
																		'fieldName'=>'Gender',
																		'key'=>false
																	),
																	array(
																		'columnNumber'=>13,
																		'fieldName'=>'Annual Income',
																		'key'=>false
																	)
																)													
															 ),
										  'csvData'=>$csvdata
										 
										
													 ));
									foreach($idsarr as $idsarr) {
										$save = array();
										$save['Lead']['id'] = $idsarr;
										$save['Lead']['five9_status'] = 1;
										$this->Lead->save($save,false);
									}
									$this->Session->setFlash('Records have been saved sucessfully.', 'Dialogs/top_right', array('type'=>'success'), 'top_right');
								}
								catch (Exception $e)
								{
									$last_req = $client->__getLastRequest();
									$last_res = $client->__getLastResponse();
									$this->Session->setFlash('Records are not saved, Please try later.', 'Dialogs/top_right', array('type'=>'error'), 'top_right');	
								}
					
						//================ End =================
						
						
						
	}
	}
}	
		$arrayAction['action']='getListsInfo';    // get five9 lead list
		$arrayAction['record']='Developerssssssss';
		$five9List=$this->common->five9_api($arrayAction);
		
		$this->set('five9List',$five9List);
	
		// get project lead list
		$this->paginate	= array('order'=>array('Lead.id desc'),'recursive'=>-1,'limit'=>PAGE_RECORD,'conditions'=>'Lead.five9_status=0');
		$leadList=$this->paginate('Lead');
		$this->set(compact('leadList'));
	}
//================ End =================
	
}
?>
<?php
/*
	 * Used to mange all human resource data
	 *  Created by : Pushkar Soni
*/

App::uses('AppController', 'Controller');

App::uses('CakeEmail', 'Network/Email');

class HomesController extends AppController {
	public $theme = 'Front';
	public $layout = 'front';
	public $name = 'Homes'; 
	public $helpers = array('Js','Html','Javascript','Text','Paginator','Ajax');
	public $uses = array('Lead','Campaign','CallScript','Rebuttal','Form','Order','Customer','LeadKeyword','Support','CallData','OrderUpsale','CampaignProduct','LeadLog');
	public $components = array('RequestHandler','Session','common');	

	public function index($leadId=null){
		$id='';	
		$number='';	
		$query=$this->request->query;
		(isset($query['number'])) ? $number=$query['number'] : $number='';
		(isset($query['Country'])) ? $country=$query['Country'] : $country='';
		(isset($query['id'])) ? $id=$query['id'] : $id='';
		(isset($query['session_id'])) ? $five9sessionid=$query['session_id'] : $five9sessionid='';
		(isset($query['full_name'])) ? $agent_name=$query['full_name'] : $agent_name='';
			
		(isset($agent_name)) ? $this->set('agent_name',$agent_name) : $this->set('agent_name','--------');
			
		//============== if lead id not found in database==============
		
		
		if(empty($id)) {
			if(isset($query['campaign_id'])) {
				
		// checking five9 campagin id is exits in our database or not
		$is_five9_campaign_exits=$this->Campaign->find('count',array('conditions'=>array('five9_campaign_id'=>$query['campaign_id'])));  
			
			if($is_five9_campaign_exits){	
			
				if(!empty($number)) {
				
				$this->Campaign->recursive=-1;
				$campagin_data=$this->Campaign->find('first',array('conditions'=>array('Campaign.five9_campaign_id'=>$query['campaign_id']),'fields'=>'Campaign.id,Campaign.customer_id'));
						
				$camp_id=$campagin_data['Campaign']['id'];
				$camp_cust_id=$campagin_data['Campaign']['customer_id'];
					
					
					
					
						
				$checkLead=$this->Lead->find('count',array('conditions'=>array('Lead.phone="'.trim($number).'" and Lead.campaign_id="'.$camp_id.'" ')));
			
/*$leadRec=$this->Lead->find('first',array('conditions'=>array('Lead.phone'=>$number),'fields'=>array('Lead.id','Lead.campaign_id','Lead.phone')));
echo '<pre>';
						print_r($leadRec);	*/


					
					if($checkLead){
						$leadRec=$this->Lead->find('first',array('conditions'=>array('Lead.phone="'.trim($number).'" and Lead.campaign_id="'.$camp_id.'" '),'fields'=>'Lead.id'));
						
						$id=$leadRec['Lead']['id'];
						$data['Lead']['phone']=$number;
						$data['Lead']['id']=$id;
						
						
					}else{
						$this->Lead->create();
						$data['Lead']['phone']=$number;
						$data['Lead']['campaign_id']=$camp_id;
						$data['Lead']['customer_id']=$camp_cust_id;
						
						$this->Lead->save($data);
						$id=$this->Lead->getLastInsertId();
						$data['Lead']['id']=$id;
					}
					$data['Lead']['listname']='newlist';
					$this->common->_updateLead($data);
				
				}
				}
			}
		}
		
	
		
		(!empty($id)) ? $this->set('leadId',$id) : $this->set('leadId',$id);
		(isset($query['campaign_name'])) ? $this->set('campagin_name',$query['campaign_name']) : $this->set('campaign_name','Campaign');
		(isset($query['user_name'])) ? $this->set('user_name',$query['user_name']) : $this->set('user_name','username');
		(isset($five9sessionid)) ? $this->set('five9sessionid',$five9sessionid) : $this->set('five9sessionid','');
		/*(isset($query['campaign_id'])) ? $this->set('campaign_id',$query['campaign_id']) : $this->set('campaign_id',0);*/
			//=================== save record for calling start=========
			
		if(isset($query['disposition_id']) and $query['disposition_id']==-1){
			
			(isset($query['campaign_id'])) ? $data['CallData']['campaign_id']=$query['campaign_id'] : $data['CallData']['campaign_id']='';
			(isset($query['campaign_name'])) ? $data['CallData']['campaign_name']=$query['campaign_name'] : $data['CallData']['campaign_name']='';
			(isset($query['first_name'])) ? $data['CallData']['first_name']=$query['first_name'] : $data['CallData']['first_name']='';
			(isset($query['last_name'])) ? $data['CallData']['last_name']=$query['last_name'] : $data['CallData']['last_name']='';
			(isset($five9sessionid)) ? $data['CallData']['session_id']=$five9sessionid : $data['CallData']['session_id']='';
			(isset($query['user_name'])) ? $data['CallData']['user_name']=$query['user_name'] : $data['CallData']['user_name']='';
			(isset($query['id'])) ? $data['CallData']['lead_id']=$query['id'] : $data['CallData']['lead_id']=$id;
			(isset($query['number'])) ? $data['CallData']['number']=$query['number'] : $data['CallData']['number']='';
			
			
			$rec=$this->CallData->find('first');
			$total_rec=$this->CallData->find('count',array('conditions'=>'CallData.session_id="'.$data['CallData']['session_id'].'"'));
			
				if(!$total_rec){
					$this->CallData->save($data);
					$this->Session->write('Call_id',$this->CallData->getLastInsertId());
				}
				
			}
			
			
		if(isset($query['disposition_id']) and $query['disposition_id']!=-1){

		$lead_id=$this->Session->read('Call_id');
		(isset($query['disposition_id'])) ? $data['CallData']['disposition_id']=$query['disposition_id'] : $data['CallData']['disposition_id']='';
		(isset($query['session_id'])) ? $data['CallData']['callend_session_id']=$query['session_id'] : $data['CallData']['callend_session_id']='';
		(isset($query['start_timestamp'])) ? $data['CallData']['callstart_time']=$query['start_timestamp'] : $data['CallData']['callstart_time']='';
		(isset($query['end_timestamp'])) ? $data['CallData']['callend_time']=$query['end_timestamp'] : $data['CallData']['callend_time']='';
		(isset($lead_id)) ? $data['CallData']['id']=$lead_id : $data['CallData']['id']='';
		
		
		//	$rec=$this->CallData->find('first');
			$total_rec1=$this->CallData->find('count',array('conditions'=>'CallData.callend_session_id="'.$data['CallData']['callend_session_id'].'"'));
				if(!$total_rec1){
					$this->CallData->save($data,false);
					$this->Session->write('Call_id','');
				}
			}
	
	}
	
	function get_call_script($leadId=null,$agent_name=null){
	
		$this->autoRender=false;
		$this->Session->write('lead_id','');
		$this->Session->write('lead_id',$leadId);
		
		$this->Lead->id=$leadId;   // campagin id
		$campaignId=$this->Lead->field('Lead.campaign_id');
		
		$leadRec=$this->Lead->find('first',array('conditions'=>'Lead.id="'.$leadId.'"'));
		$orderRec=$this->Order->find('first',array('conditions'=>'Order.lead_id="'.$leadId.'"'));
		
		
		$rec=$this->Campaign->find('first',array('conditions'=>'Campaign.id="'.$campaignId.'"'));
		
		$keyList=$this->common->get_campagin_keyowrd_key_list($campaignId);
		$valueList=$this->common->get_campagin_keyowrd_value_list($campaignId);
		
		$leadKeyword=$this->LeadKeyword->find('list',array('fields'=>'LeadKeyword.keyword,LeadKeyword.description'));
		
		
		foreach($rec['CallScript'] as $key=>$value)
		{
			$rec['CallScript'][$key]['script']=str_replace($keyList,$valueList,$value['script']);
			
			foreach($leadKeyword as $key2=>$value2)
			{
				switch($key2){
				
				case '[[D_FIRST_NAME]]':
							$rec['CallScript'][$key]['script']=str_replace($key2,$leadRec['Lead']['first_name'],$rec['CallScript'][$key]['script']);
							break;
				case '[[D_LAST_NAME]]':
							$rec['CallScript'][$key]['script']=str_replace($key2,$leadRec['Lead']['last_name'],$rec['CallScript'][$key]['script']);
							break;
							
				case '[[D_ZIP_CODE]]':
							$rec['CallScript'][$key]['script']=str_replace($key2,$leadRec['Lead']['zip_code'],$rec['CallScript'][$key]['script']);
							break;
				
				case '[[S_FIRST_NAME]]':
							$rec['CallScript'][$key]['script']=str_replace($key2,$orderRec['Order']['ship_fname'],$rec['CallScript'][$key]['script']);
							break;
							
				case '[[S_LAST_NAME]]':
							$rec['CallScript'][$key]['script']=str_replace($key2,$orderRec['Order']['ship_lname'],$rec['CallScript'][$key]['script']);
							break;
							
				case '[[S_PHONE_NUMBER]]':
							$rec['CallScript'][$key]['script']=str_replace($key2,$orderRec['Order']['ship_phone_number'],$rec['CallScript'][$key]['script']);
							break;
				
				case '[[S_EMAIL_ADDRESS]]':
							$rec['CallScript'][$key]['script']=str_replace($key2,$orderRec['Order']['ship_email'],$rec['CallScript'][$key]['script']);
							break;
				
				case '[[S_CITY_NAME]]':
							$rec['CallScript'][$key]['script']=str_replace($key2,$orderRec['Order']['ship_city'],$rec['CallScript'][$key]['script']);
							break;			
							
				case '[[B_FIRST_NAME]]':
							$rec['CallScript'][$key]['script']=str_replace($key2,$orderRec['Order']['bill_fname'],$rec['CallScript'][$key]['script']);
							break;
							
				case '[[B_LAST_NAME]]':
							$rec['CallScript'][$key]['script']=str_replace($key2,$orderRec['Order']['bill_lname'],$rec['CallScript'][$key]['script']);
							break;
							
				case '[[B_PHONE_NUMBER]]':
							$rec['CallScript'][$key]['script']=str_replace($key2,$orderRec['Order']['bill_phone_number'],$rec['CallScript'][$key]['script']);
							break;
				
				case '[[B_EMAIL_ADDRESS]]':
							$rec['CallScript'][$key]['script']=str_replace($key2,$orderRec['Order']['bill_email'],$rec['CallScript'][$key]['script']);
							break;
							
				case '[[B_CITY_NAME]]':
							$rec['CallScript'][$key]['script']=str_replace($key2,$orderRec['Order']['bill_city'],$rec['CallScript'][$key]['script']);
							break;	
									
				default :
						break;
				
				
				}
				
				
				$rec['CallScript'][$key]['script']=str_replace('[[AGNET_NAME]]',$agent_name,$rec['CallScript'][$key]['script']);
			}
			
			/*switch($value['form_id']){
			case 1:
					$rec['CallScript'][$key]['form_id']=file_get_contents(BASE_URL.'app/View/Themed/Front/Homes/shipping.ctp');
					break;
			case 2:
					$rec['CallScript'][$key]['form_id']=file_get_contents(BASE_URL.'app/View/Themed/Front/Homes/billing.ctp');
					break;
			case 3:
					$rec['CallScript'][$key]['form_id']=file_get_contents(BASE_URL.'app/View/Themed/Front/Homes/default.ctp');
					break;
				
			default:
				break;
			}*/
		}
		
		
		
		
		
		echo json_encode($rec['CallScript']);		
	}
	
	function get_call_rebuttals($leadId=null){
		ini_set('memory_limit', '1000M'); //Raise to 512 MB
ini_set('max_execution_time', '20000'); //Raise to 512 MB
	
		$this->autoRender=false;
		
		$this->Session->write('lead_id','');
		$this->Session->write('lead_id',$leadId);
		$this->Lead->id=$leadId;
		$campaignId=$this->Lead->field('Lead.campaign_id');
	
		$LeadRec=$this->Campaign->find('first',array('recursive'=>2,'conditions'=>'Campaign.id="'.$campaignId.'"'));
		
	
		
		
				
		$rebutal_list=array();
		$i=0;
		
		$call_script='';
		$call_script=$LeadRec['CallScript'];
		
		foreach($call_script as $value)
		{
			foreach($value['Rebuttal'] as $value)
			{
				$rebutal_list[$i]['Rebuttal']['id']=$value['id'];
				$rebutal_list[$i]['Rebuttal']['call_script_id']=$value['call_script_id'];
				$rebutal_list[$i]['Rebuttal']['objection']=$value['objection'];
				$rebutal_list[$i]['Rebuttal']['rebuttals']=$value['rebuttals'];
				$rebutal_list[$i]['Rebuttal']['active']=$value['active'];
				$rebutal_list[$i]['Rebuttal']['created']=$value['created'];
				$rebutal_list[$i]['Rebuttal']['modified']=$value['modified'];
			$i=$i+1;
			}
		}
		
		

		
		$keyList=$this->common->get_campagin_keyowrd_key_list($campaignId);
		$valueList=$this->common->get_campagin_keyowrd_value_list($campaignId);
	
	
	
		$leadRc=$this->Lead->find('first',array('conditions'=>'Lead.id="'.$leadId.'"'));
		$orderRc=$this->Order->find('first',array('conditions'=>'Order.lead_id="'.$leadId.'"'));
		$leadKeyword=$this->LeadKeyword->find('list',array('fields'=>'LeadKeyword.keyword,LeadKeyword.description'));
	
		
		$this->autoRender=false;
		//$rec=$this->Rebuttal->find('all',array('conditions'=>'Rebuttal.call_script_id="'.$callScriptId.'"'));
		$rebuttal=$rebutal_list;
		foreach($rebutal_list as $key=>$value){
		
			$rebutal_list[$key]['Rebuttal']['objection']=str_replace($keyList,$valueList,$value['Rebuttal']['objection']);
			$rebutal_list[$key]['Rebuttal']['rebuttals']=str_replace($keyList,$valueList,$value['Rebuttal']['rebuttals']);
			
			
			foreach($leadKeyword as $key2=>$value2)
			{
				switch($key2){
				
				case '[[D_FIRST_NAME]]':
							$rebutal_list[$key]['Rebuttal']['objection']=str_replace($key2,$leadRc['Lead']['first_name'],$rebutal_list[$key]['Rebuttal']['objection']);
							$rebutal_list[$key]['Rebuttal']['rebuttals']=str_replace($key2,$leadRc['Lead']['first_name'],$rebutal_list[$key]['Rebuttal']['rebuttals']);
							break;
				case '[[D_LAST_NAME]]':
							$rebutal_list[$key]['Rebuttal']['objection']=str_replace($key2,$leadRc['Lead']['last_name'],$rebutal_list[$key]['Rebuttal']['objection']);
							$rebutal_list[$key]['Rebuttal']['rebuttals']=str_replace($key2,$leadRc['Lead']['last_name'],$rebutal_list[$key]['Rebuttal']['rebuttals']);
							
							break;
							
				case '[[D_ZIP_CODE]]':
							
							$rebutal_list[$key]['Rebuttal']['objection']=str_replace($key2,$leadRc['Lead']['zip_code'],$rebutal_list[$key]['Rebuttal']['objection']);
							$rebutal_list[$key]['Rebuttal']['rebuttals']=str_replace($key2,$leadRc['Lead']['zip_code'],$rebutal_list[$key]['Rebuttal']['rebuttals']);
							break;
				
				case '[[S_FIRST_NAME]]':
							
							$rebutal_list[$key]['Rebuttal']['objection']=str_replace($key2,$orderRc['Order']['ship_fname'],$rebutal_list[$key]['Rebuttal']['objection']);
							$rebutal_list[$key]['Rebuttal']['rebuttals']=str_replace($key2,$orderRc['Order']['ship_fname'],$rebutal_list[$key]['Rebuttal']['rebuttals']);
							
							break;
							
				case '[[S_LAST_NAME]]':
						
							
							$rebutal_list[$key]['Rebuttal']['objection']=str_replace($key2,$orderRc['Order']['ship_lname'],$rebutal_list[$key]['Rebuttal']['objection']);
							$rebutal_list[$key]['Rebuttal']['rebuttals']=str_replace($key2,$orderRc['Order']['ship_lname'],$rebutal_list[$key]['Rebuttal']['rebuttals']);
							break;
							
				case '[[S_PHONE_NUMBER]]':
							
							
							$rebutal_list[$key]['Rebuttal']['objection']=str_replace($key2,$orderRc['Order']['ship_phone_number'],$rebutal_list[$key]['Rebuttal']['objection']);
							$rebutal_list[$key]['Rebuttal']['rebuttals']=str_replace($key2,$orderRc['Order']['ship_phone_number'],$rebutal_list[$key]['Rebuttal']['rebuttals']);
							break;
				
				case '[[S_EMAIL_ADDRESS]]':
							
							
							$rebutal_list[$key]['Rebuttal']['objection']=str_replace($key2,$orderRc['Order']['ship_email'],$rebutal_list[$key]['Rebuttal']['objection']);
							$rebutal_list[$key]['Rebuttal']['rebuttals']=str_replace($key2,$orderRc['Order']['ship_email'],$rebutal_list[$key]['Rebuttal']['rebuttals']);
							
							break;
							
				case '[[S_CITY_NAME]]':
							$rebutal_list[$key]['Rebuttal']['objection']=str_replace($key2,$orderRc['Order']['ship_city'],$rebutal_list[$key]['Rebuttal']['objection']);
							$rebutal_list[$key]['Rebuttal']['rebuttals']=str_replace($key2,$orderRc['Order']['ship_city'],$rebutal_list[$key]['Rebuttal']['rebuttals']);
							break;				
							
				case '[[B_FIRST_NAME]]':
														
							$rebutal_list[$key]['Rebuttal']['objection']=str_replace($key2,$orderRc['Order']['bill_fname'],$rebutal_list[$key]['Rebuttal']['objection']);
							$rebutal_list[$key]['Rebuttal']['rebuttals']=str_replace($key2,$orderRc['Order']['bill_fname'],$rebutal_list[$key]['Rebuttal']['rebuttals']);
							break;
							
				case '[[B_LAST_NAME]]':
							
							
							$rebutal_list[$key]['Rebuttal']['objection']=str_replace($key2,$orderRc['Order']['bill_lname'],$rebutal_list[$key]['Rebuttal']['objection']);
							$rebutal_list[$key]['Rebuttal']['rebuttals']=str_replace($key2,$orderRc['Order']['bill_lname'],$rebutal_list[$key]['Rebuttal']['rebuttals']);
							break;
							
				case '[[B_PHONE_NUMBER]]':
						
							
							$rebutal_list[$key]['Rebuttal']['objection']=str_replace($key2,$orderRc['Order']['bill_phone_number'],$rebutal_list[$key]['Rebuttal']['objection']);
							$rebutal_list[$key]['Rebuttal']['rebuttals']=str_replace($key2,$orderRc['Order']['bill_phone_number'],$rebutal_list[$key]['Rebuttal']['rebuttals']);
							break;
				
				case '[[B_EMAIL_ADDRESS]]':
							
							$rebutal_list[$key]['Rebuttal']['objection']=str_replace($key2,$orderRc['Order']['bill_email'],$rebutal_list[$key]['Rebuttal']['objection']);
							$rebutal_list[$key]['Rebuttal']['rebuttals']=str_replace($key2,$orderRc['Order']['bill_email'],$rebutal_list[$key]['Rebuttal']['rebuttals']);
							break;
				
				case '[[B_CITY_NAME]]':
							$rebutal_list[$key]['Rebuttal']['objection']=str_replace($key2,$orderRc['Order']['bill_city'],$rebutal_list[$key]['Rebuttal']['objection']);
							$rebutal_list[$key]['Rebuttal']['rebuttals']=str_replace($key2,$orderRc['Order']['bill_city'],$rebutal_list[$key]['Rebuttal']['rebuttals']);
							break;			
							
				default :
						break;
				
				
				}
				
			}
			
			
			
			
			
			
		}
		
		echo json_encode($rebutal_list);	
		die;	
	}
	
	function get_rebuttal_detail($rebuttalId=null)
	{
		$this->autoRender=false;
		$rec=$this->Rebuttal->find('first',array('conditions'=>'Rebuttal.id="'.$rebuttalId.'"'));
		
		
		$this->CallScript->id=$rec['Rebuttal']['call_script_id'];
		$campaignId=$this->CallScript->field('CallScript.campaign_id');
		$keyList=$this->common->get_campagin_keyowrd_key_list($campaignId);
		$valueList=$this->common->get_campagin_keyowrd_value_list($campaignId);
		$rec['Rebuttal']['rebuttals']=str_replace($keyList,$valueList,$rec['Rebuttal']['rebuttals']);
	
			
		echo json_encode($rec['Rebuttal']);		
	}
	
	function get_form_html($form_id=null,$lead_id=null,$script_id=null)
	{
		$this->Form->id=$form_id;
		$ctp_name=$this->Form->field('Form.name');
		
		$this->loadModel('State');
		$stateList=$this->State->find('list',array('fields'=>'state_abrev,state_name'));
		$this->set('stateList',$stateList);
	
		if($ctp_name){
			$ctp=$ctp_name.'.ctp';
			switch($ctp)
			{
				case 'shipping.ctp':
					$this->Session->write('shipping_information','');
					$this->request->data=$this->Order->find('first',array('conditions'=>'Order.lead_id="'.$lead_id.'"'));
					
					
					
					$lead=$this->Lead->findById($lead_id);  
					if(!$this->request->data['Order']['ship_email']){ $this->request->data['Order']['ship_email']=$lead['Lead']['email']; }
					if(!$this->request->data['Order']['ship_fname']){ 	$this->request->data['Order']['ship_fname']=$lead['Lead']['first_name']; }
					if(!$this->request->data['Order']['ship_lname']){ 	$this->request->data['Order']['ship_lname']=$lead['Lead']['last_name']; }
					
					
					
					$this->render($ctp_name);
					break;
					
				case 'billing.ctp':
					
					$this->Lead->recursive=-1;
					
					$rec=$this->Lead->findById($lead_id);
					$customer_id=$rec['Lead']['customer_id'];
					$campaign_id=$rec['Lead']['campaign_id'];
					$email=$rec['Lead']['email'];
					
					
					
					$this->set('customer_id',$rec['Lead']['customer_id']);
					$this->set('campaign_id',$campaign_id);
					
					$this->request->data=$this->Order->find('first',array('conditions'=>'Order.lead_id="'.$lead_id.'"'));
					$this->request->data['Order']['cc_number']='';
					$this->request->data['Order']['cc_expiration_month']='';
					$this->request->data['Order']['cc_expiration_year']='';
					$this->request->data['Order']['cvv_number']='';
					
					if(!$this->request->data['Order']['bill_email']){ $this->request->data['Order']['bill_email']=$rec['Lead']['email']; }
					if(!$this->request->data['Order']['bill_fname']){ 	$this->request->data['Order']['bill_fname']=$rec['Lead']['first_name']; }
					if(!$this->request->data['Order']['bill_lname']){ 	$this->request->data['Order']['bill_lname']=$rec['Lead']['last_name']; }
					
				
					if(!empty($campaign_id)){   // get payment gatway for select
						
						$this->Campaign->id=$campaign_id;
						$paymentgatway=$this->Campaign->field('paymentgetwaylist_id');
						$this->set('paymentgatway',$paymentgatway);			
					}
					
					$this->render($ctp_name);
					break;
					
				case 'varification.ctp':
					$lead_id=$lead_id;
					$rec=$this->Lead->findById($lead_id);
					$this->set(compact('lead_id'));
					$customer_id=$rec['Lead']['customer_id'];
					$campaign_id=$rec['Lead']['campaign_id'];
					
					
					
					$rec=$this->Lead->findById($lead_id);  // lead record
					$this->request->data=$rec;
					
					
					$callscript_rec=$this->CallScript->findById($script_id);  // get call script rec
					$this->set('optout',$callscript_rec['CallScript']['optedout']);
					$this->set('form_id',$callscript_rec['CallScript']['form_id']);
					
					
					if(!empty($campaign_id)){   // get payment gatway for select
						$this->Campaign->id=$campaign_id;
						$paymentgatway=$this->Campaign->field('paymentgetwaylist_id');
						$this->set(compact('paymentgatway'));			
					}
					
					
					$this->render($ctp_name);
					break;
						
				case 'default.ctp':
					$this->Lead->recursive=-1;
					
					$rec=$this->Lead->findById($lead_id);
					$this->request->data=$rec;
					
					$this->render($ctp_name);
					break;
				
				case 'upsell.ctp':
						$this->Lead->recursive=-1;
					
						$rec=$this->Lead->findById($lead_id);
						$customer_id=$rec['Lead']['customer_id'];
						$campaign_id=$rec['Lead']['campaign_id'];
						$this->set(compact('campaign_id'));
						
						$this->render($ctp_name);
						break;
				
				default:
						$this->autoRender=false;
						echo '';
						break;
								
				
			}
			
		}else{
			echo '';
			$this->autoRender=false;
			die;
		}
	}
	
	function submit_form()
	{
		$this->autoRender=false;
		$this->request->data['Order']['lead_id']=$this->Session->read('lead_id');
		if($this->request->data)
		{
			
			if(isset($this->request->data['Order']['SUBMIT']))
			{
				switch($this->request->data['Order']['SUBMIT'])
				{
					case 'SHIPPING':
							
					($this->request->data['Order']['ship_fname']=='First Name') ? $this->request->data['Order']['ship_fname']='' : $test='';
					($this->request->data['Order']['ship_lname']=='Last Name') ? $this->request->data['Order']['ship_lname']='' : $test='';
					($this->request->data['Order']['ship_address1']=='Address 1') ? $this->request->data['Order']['ship_address1']='' : $test='';
					($this->request->data['Order']['ship_address2']=='Address 2') ? $this->request->data['Order']['ship_address2']='' : $test='';
					($this->request->data['Order']['ship_phone_number']=='Phone Number') ? $this->request->data['Order']['ship_phone_number']='' : $test='';
					($this->request->data['Order']['ship_email']=='E-mail Address') ? $this->request->data['Order']['ship_email']='' : $test='';
					($this->request->data['Order']['ship_city']=='City') ? $this->request->data['Order']['ship_city']='' : $test='';
					($this->request->data['Order']['ship_state']=='State') ? $this->request->data['Order']['ship_state']='' : $test='';
					($this->request->data['Order']['ship_zip']=='Zip Code') ? $this->request->data['Order']['ship_zip']='' : $test='';
							
					$message=array();
					$this->Order->set($this->request->data['Order']);
					
							
							if(!$this->Order->shipping_validation()){
								$message=array('message'=>'error',
												'error_type'=>'validation',
												'error'=>$this->Order->validationErrors);
								echo json_encode($message);
							}else{
								
								$this->Session->Write('shipping_information',$this->request->data); // set session
								
								$message=array('message'=>'success',
												'success_type'=>'simple',
												'success'=>'Record has been saved successfully');
								echo json_encode($message);	
							}
						
							break;
					
					case 'UPSELL':
							$this->Session->Write('data.OrderUpsale',$this->request->data['OrderUpsale']);
							$message=array('message'=>'success',
												'success_type'=>'simple',
												'success'=>'Record has been saved successfully');
							echo json_encode($message);	
						break;
					
					case 'VARIFICATION':
							
						
						
						($this->request->data['Order']['bill_address11']=='Address 1') ? $this->request->data['Order']['bill_address11']='' : $bill_address1='';
						($this->request->data['Order']['bill_address22']=='Address 2') ? $this->request->data['Order']['bill_address22']='' : $bill_address2='';
						($this->request->data['Order']['bill_state1']=='State') ? $this->request->data['Order']['bill_state1']='' : $bill_state='';
						
						($this->request->data['Order']['bill_city1']=='City') ? $this->request->data['Order']['bill_city1']='' : $bill_city='';
						($this->request->data['Order']['bill_zip1']=='Zip Code') ? $this->request->data['Order']['bill_zip1']='' : $bill_zip='';
						
							
							$this->Order->set($this->request->data);
							if(!$this->Order->varification_validation2()){
									
									
									$message=array('message'=>'error',
												'error_type'=>'validation',
												'error'=>$this->Order->validationErrors);
									echo json_encode($message);	
									
									
								}else{
									
									$data=$this->Session->read('data');
									unset($data['Order']['bill_address1']);
									unset($data['Order']['bill_address2']);
									unset($data['Order']['bill_zip']);
									unset($data['Order']['bill_city']);
									unset($data['Order']['bill_state']);
									unset($data['Order']['cc_card_type']);
									unset($data['Order']['cc_number']);
									unset($data['Order']['cc_expiration_month']);
									unset($data['Order']['cc_expiration_year']);
									unset($data['Order']['cvv_number']);
									
									
									
									$data['Order']['bill_address1']=trim($this->request->data['Order']['bill_address11']);
									$data['Order']['bill_address2']=trim($this->request->data['Order']['bill_address22']);
									$data['Order']['bill_zip']=trim($this->request->data['Order']['bill_zip1']);
									$data['Order']['bill_city']=trim($this->request->data['Order']['bill_city1']);
									$data['Order']['bill_state']=trim($this->request->data['Order']['bill_state1']);
									//$data['Order']['cc_card_type']=trim($this->request->data['Order']['cc_card_type1']);
									$data['Order']['cc_card_type']=$this->common->creditCardType(trim($this->request->data['Order']['cc_number1']));
									$data['Order']['cc_number']=trim($this->request->data['Order']['cc_number1']);
									$data['Order']['cc_expiration_month']=trim($this->request->data['Order']['cc_expiration_month1']);
									$data['Order']['cc_expiration_year']=trim($this->request->data['Order']['cc_expiration_year1']);
									$data['Order']['cvv_number']=trim($this->request->data['Order']['cvv_number1']);
									
										//================= code for payment =================
										$sfirstname=$data['Order']['ship_fname'];  //=== shipping detail ====
										$slastname=$data['Order']['ship_lname'];
										$scity=trim($data['Order']['ship_city']);
										$sstate=trim($data['Order']['ship_state']);
										$scountry='US';
										$saddress1=trim($data['Order']['ship_address1']);
										$saddress2=trim($data['Order']['ship_address2']);
										$szipcode=trim($data['Order']['ship_zip']);
										$ship_phonenumber=trim($data['Order']['ship_phone_number']);
										$ship_email=trim($data['Order']['ship_email']);
										
										$cardnumber=trim($data['Order']['cc_number']);  //==== card deail ====
										$cardcvv=trim($data['Order']['cvv_number']);
										$cardtype=trim($data['Order']['cc_card_type']);
										$expirymonth=trim($data['Order']['cc_expiration_month']);
										$expiryYear=trim($data['Order']['cc_expiration_year']);
							
										
									
									
										//===========================================
										$bfirstname=trim($data['Order']['bill_fname']); // === billing detail ==
										$blastname=trim($data['Order']['bill_lname']);
										$baddress1=trim($data['Order']['bill_address1']);
										$baddress2=trim($data['Order']['bill_address2']);
										$bcity=trim($data['Order']['bill_city']);
										$bstate=trim($data['Order']['bill_state']);
										$bzipcode=trim($data['Order']['bill_zip']);
										$bcountry='US';
										///=======assgin shipping information to shipping information=====
							
							
										($data['Order']['campaign_id']) ? $camp_id=$data['Order']['campaign_id'] : $camp_id='';
								
										if(!empty($camp_id)){
								
								//$paymentgatway=$this->Campaign->field('paymentgetwaylist_id'); 
								
								$this->Campaign->recursive=-2;
								$rec=$this->Campaign->findById($camp_id);
								$paymentgatway=$rec['Campaign']['paymentgetwaylist_id'];
								$data['Order']['ereports_store_id']=$rec['Campaign']['ereports_store_id'];  // ereports payment
								$this->request->data['Order']['ereports_token']=$rec['Campaign']['ereports_token'];
								///======
								$data['Order']['mediahub_apikey']=$rec['Campaign']['mediahub_apikey']; // media humb api details
								$data['Order']['mediahub_token']=$rec['Campaign']['mediahub_token'];
								$this->request->data['Order']['publisher_id']=$rec['Campaign']['publisher_id'];
								//// ======
								$data['Order']['lime_light_username']=$rec['Campaign']['lime_light_username']; // lime light api details
								$data['Order']['lime_light_password']=$rec['Campaign']['lime_light_password'];
								
								
								// get slected product information
								$this->CampaignProduct->recursive=-2;
								$prodcut_id=$data['Order']['product_id'];
								$produt_rec=$this->CampaignProduct->findById($prodcut_id);  // get prodcut record 
						
							
								
							(!empty($produt_rec['CampaignProduct']['disposition_id'])) ? $disposition_id=$produt_rec['CampaignProduct']['disposition_id'] : $disposition_id='';
								
								$data['Order']['ereports_price']=$produt_rec['CampaignProduct']['total_price'];
								$data['Order']['lime_light_campaign_id']=$produt_rec['CampaignProduct']['lime_light_campaign_id']; 
								$data['Order']['lime_light_shipping_id']=$produt_rec['CampaignProduct']['lime_light_shipping_id'];
								$data['Order']['lime_light_product_id']=$produt_rec['CampaignProduct']['product_id'];
								$data['Order']['name']=$produt_rec['CampaignProduct']['name'];
								$product1_name=trim($produt_rec['CampaignProduct']['name']);
								$product1_qty=1;
								$product1_totalprice=0.01;
								
								if(isset($paymentgatway)){    // select payment getway
											switch($paymentgatway){
													case 1:
								//token:- 433ca874-ccf0-473d-9d58-981f552709d4
								//storeid:- 363
								//storeid:- 363
								// pid :- 403
								
								
							
								
								$url = 'https://gateway.sslapplications.net/ynMklop/?' . http_build_query(array(
																									'type' => 'sale',
																									'token' => $rec['Campaign']['ereports_token'],
																									'storeid' => $rec['Campaign']['ereports_store_id'],
																									'offer' => 'default',
																									'saleorigin' => 'cebuglobaltel.com',
																									'bsameasshipping' => 'N',
																									'sfirstname' => $sfirstname,
																									'slastname' => $slastname,
																									'scity' => $scity,
																									'sstate' => $sstate,
																									'saddress1' => $saddress1,
																									'szipcode' => $szipcode,
																									
																									'bfirstname' => $bfirstname,
																									'blastname' => $blastname,
																									'baddress1' => $baddress1,
																									'baddress2' => $baddress2,
																									'bcity' => $bcity,
																									'bstate' => $bstate,
																									'bzipcode' => $bzipcode,
																									'bcountry' => $bcountry,
																									'cardnumber' => $cardnumber,
																									'expirymonth' => $expirymonth,
																									'expiryYear' => $expiryYear,
																									'pid' => $prodcut_id,
																									'product1_name' =>$data['Order']['name'],
																									'product1_qty' => 1,
																									'product1_totalprice' =>$data['Order']['ereports_price'],
																									'test' => 1	
																								));				
										
															
														$options = array(
																	CURLOPT_RETURNTRANSFER => true,     // return web page
																	CURLOPT_HEADER         => false,    // don't return headers
																	CURLOPT_FOLLOWLOCATION => true,     // follow redirects
																	CURLOPT_ENCODING       => '',       // handle all encodings
																	CURLOPT_AUTOREFERER    => true,     // set referer on redirect
																	CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
																	CURLOPT_TIMEOUT        => 120,      // timeout on response
																	CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
																	CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
																);
									
																$ch      = curl_init( $url );
																curl_setopt_array( $ch, $options );
															 	
																$content =urldecode(curl_exec($ch));
																curl_close($ch);
															
																$resp='';
																$result=explode('&',$content);
																
																foreach($result as $value){
																	$rec=explode('=',$value);
																	$payment_response[$rec[0]]=$rec[1];
																}					
																
														break;
													case 2:
														/* 'CampaignID'=>'127',
																		 'Upsale2ID'=>'128',
																		'Upsale1ID'=>'128',
																		'PublisherID'=>'10',*/
														
														$fields = array(
																		 'IPAddress'=>$_SERVER["REMOTE_ADDR"],
																		 'CampaignID'=>$produt_rec['CampaignProduct']['lime_light_campaign_id'],
																		'PublisherID'=>$rec['Campaign']['publisher_id'],
																		/*'Upsale2ID'=>'128',
																		'Upsale1ID'=>'128',*/
																		'SubID'=>'Sales Rep',
																		'FirstName'=>$sfirstname,
																		'LastName'=>$slastname,
																		'HomePhone'=>'1236985478',
																		'EmailAddress'=>'4003@dothejob.org',
																		'BillAddress'=>$baddress1,
																		'BillCity'=>$bcity,
																		'BillState'=>$bstate,
																		'BillZip'=>$bzipcode,
																		'BillCountry'=>'USA',
																		'CardNum'=>$cardnumber,
																		'CardCode'=>$cardcvv,
																		'CardType'=>$cardtype,
																		'ExpMonth'=>$expirymonth,
																		'ExpYear'=>$expiryYear,
																		'ShippingAddress1'=>$saddress1,
																		'ShippingCity'=>$scity,
																		'ShippingState'=>$sstate,
																		'ShippingZip'=>$szipcode,
																		'ShippingCountry'=>'USA',
																		'TheHash'=>'1369852770',
																		'apikey'=>$data['Order']['mediahub_apikey'],
																		'token'=>$data['Order']['mediahub_token']
																		);
																		
																if(count($data['OrderUpsale'])){
																	$i=1;
																		foreach($data['OrderUpsale'] as $key=>$value){
																			$fields['Upsale'.$i.'ID']=$value['campaign_upsale_id'];
																			$i=$i+1;
																		}
																	}
																		
																
																	
																	// apikey:- 2b899dea7fa654634dca0eb0d0411d83a4e99d64
																	// token:- c9e0c74b5f98acfdf258b46213437769
																	//	TheHash=1369852770&apikey=2b899dea7fa654634dca0eb0d0411d83a4e99d64&token=
														
															$url='https://www.mediahub.bz/api/newclientsimple/';
														
															$postfields=http_build_query($fields);
																		$url='https://www.mediahub.bz/api/newclientsimple/';
																			$options = array(
																			CURLOPT_RETURNTRANSFER => true,     // return web page
																			CURLOPT_HEADER         => false,    // don't return headers
																			CURLOPT_FOLLOWLOCATION => true,     // follow redirects
																			CURLOPT_ENCODING       => "",       // handle all encodings
																			CURLOPT_POSTFIELDS	   => $postfields,
																			CURLOPT_POST			=> true,
																			CURLOPT_SSL_VERIFYPEER	=> false,
																			CURLOPT_AUTOREFERER    => true,     // set referer on redirect
																			CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
																			CURLOPT_TIMEOUT        => 120,      // timeout on response
																			CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
																			CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
																		);
															
															$ch      = curl_init($url);
															curl_setopt_array($ch,$options);
															$content = curl_exec($ch);
															curl_close( $ch );
															
													$message_array=explode('-',$content);
													($message_array[0]=='Error') ? $payment_response['success']='No' : $payment_response['success']='No';
													(isset($message_array[1])) ? $payment_response['message']=$message_array[1] : $payment_response['message']=$message_array[1];
													
													break;
												
												case 3:
														
														$upsale_string='';
														if(count($data['OrderUpsale'])){
															foreach($data['OrderUpsale'] as $key=>$value){
																if($upsale_string){
																	$upsale_string.=',';
																}
																$upsale_string.=$value['campaign_upsale_id'];
															}
														}
														$data['Order']['upsale_product_id']=$upsale_string;
														
														
														App::import('Vendor','LimeLightProxy');  // integrate lime light
														$proxy = new LimeLightProxy($_SERVER);
														
														 $result = $proxy->createNewOrder($data);
														
														 if ($result["errorFound"]) {
															$payment_response['success']='No';
															$payment_response['message']=urldecode($result["errorMessage"]);
														} else {
															$payment_response['success']='Yes';
															$payment_response['message']='Payment has been done successfull <br> OrderID = '.$result["orderId"];
														}
																											
														break;
												
													case 4:
															
															
															$fields = array(
																			'method'=>'donate',
																			'api_key'=>$rec['Campaign']['donation_api_key'],
																			'form_id'=>$rec['Campaign']['donation_form_id'],
																			'level_id'=>$rec['Campaign']['donation_level_id'],
																		//	'center_id'=>$rec['Campaign']['donation_center_id'],
																			//'api_key'=>'01a93095eb5e31e4f27cf7760134be28',
																		//	'form_id'=>'4420',
																			//'level_id'=>'6722',
																			//'center_id'=>$rec['Campaign']['donation_center_id'],
																			
																			'v'=>'1.0',
																			
																			'response_format'=>'xml',
																			'billing.address.city'=>$scity,
																			'billing.address.state'=>$bstate,
																			'billing.address.zip'=>$bzipcode,
																			'billing.address.country'=>'US',
																			'billing.address.street1'=>'150 North Radnor Chester Road Suite F-200',
																			'billing.address.street2'=>'150 North Radnor Chester Road Suite F-200',
																			'billing.name.first'=>$bfirstname,
																			'billing.name.last'=>$blastname,
																			'donor.email'=>'4003@dothejob.org',
																			
																			// card type ===
																			'card_cvv'=>$cardcvv,
																			'card_exp_date_month'=>$expirymonth,
																			'card_exp_date_year'=>$expiryYear,
																			'card_number'=>$cardnumber,
																			'other_amount'=>$data['Order']['donation_amount'],
																		//	'other_amount'=>0.1,
																		//	'JServSessionIdr004'=>'nor5y82jy5.app202a',
																		//	'suppress_response_codes'=>true,
																			
																			
																			// ==card information
																			
																			'shipping.address.city'=>$scity,
																			'shipping.address.country'=>'US',
																			'shipping.address.state'=>$sstate,
																			'shipping.address.street1'=>$saddress1,
																			'shipping.address.street2'=>$saddress2,
																			'shipping.address.zip'=>$sstate,
																			'shipping.email'=>'',
																			'shipping.name.first'=>$sfirstname,
																			'shipping.name.last'=>$slastname,
																			'shipping.phone'=>''	
																		);
																		
														//print_r($fields);die;
														
															$url='https://secure2.convio.net/organization/site/CRDonationAPI';
														
															$postfields=http_build_query($fields);
																		$url='https://secure2.convio.net/afft/site/CRDonationAPI';
																			$options = array(
																			CURLOPT_HTTPHEADER	=>array("Content-Type: application/x-www-form-urlencoded"),
																			CURLOPT_RETURNTRANSFER => true,     // return web page
																			CURLOPT_HEADER         => false,    // don't return headers
																			CURLOPT_FOLLOWLOCATION => true,     // follow redirects
																			CURLOPT_ENCODING       => "",       // handle all encodings
																			CURLOPT_POSTFIELDS	   => $postfields,
																			CURLOPT_POST			=> true,
																			CURLOPT_SSL_VERIFYPEER	=> false,
																			CURLOPT_AUTOREFERER    => true,     // set referer on redirect
																			CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
																			CURLOPT_TIMEOUT        => 120,      // timeout on response
																			CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
																			CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
																		);
															
															$ch      = curl_init($url);
															curl_setopt_array($ch,$options);
															$content = curl_exec($ch);
															curl_close( $ch );
															
															
															$Response = json_decode(json_encode((array)simplexml_load_string($content)),1);
															
															//print_r($Response);die;
															
	
												if(isset($Response['errors']['message'])){
														
															$error_msg=$Response['errors']['code'].'<br>';
															$error_msg.=$Response['errors']['message'].'<br>';
															$error_msg.=$Response['errors']['declineUserMessage'].'<br>';
														
															$payment_response['success']='No';
															$payment_response['message']=urldecode($error_msg);
														} else {
															//print_r($Response);die;
															$payment_response['success']='Yes';
															$payment_response['message']='Payment has been done successfull';
														}
															
														break;
													
														
													default:
														break;
												}
												
												
													
											if($payment_response['success']=='Yes'){ // if payment done 
												
													$string=$data['Order']['cc_number'];
													$ccsub_string=substr($string,4,9);
	
													$data['Order']['cc_number']=str_replace($ccsub_string,'*********',$string);
										
													$string=$data['Order']['cc_number'];
													$ccsub_string=substr($string,4,9);
													$data['Order']['cc_number']=str_replace($ccsub_string,'*********',$string);
													$data['Order']['cc_expiration_month']='';
													$data['Order']['cc_expiration_year']='';
													$data['Order']['cvv_number']='';
										
													if(!empty($data['Order']['id']))
													{
														$order_id=$data['Order']['id'];
														$this->OrderUpsale->query('delete FROM order_upsales  where order_id='.$order_id);
													}
												
											
													if($this->Order->saveAll($data,false)){
													
													if(empty($data['Order']['id']))
													{
														$order_id=$this->Order->getLastInsertId();
													}else{
														$order_id=$data['Order']['id'];
													}
													$this->Order->id=$order_id;
													
													
													
													$all=$this->Order->findAllById($order_id);
													$message=array('message'=>'success',
																'success_type'=>'payment',
																'success'=>$payment_response['message'],
																'disposition_id'=>$disposition_id,
																'record'=>$all
																);
													echo json_encode($message);	
												
												}
												}else{
													$message=array('message'=>'error',
																	'error_type'=>'payment',
																	'disposition_id'=>$disposition_id,
																	'error'=>$payment_response['message']
																);
													echo json_encode($message);		
												}
										
										}else{
											
											if($this->Order->saveAll($this->request->data)){
													
													if(empty($data['Order']['id']))
													{
														$order_id=$this->Order->getLastInsertId();
													}else{
														$order_id=$data['Order']['id'];
													}
													
													$this->Order->id=$order_id;
													
													$all=$this->Order->findAllById($order_id);
													$message=array('message'=>'success',
																'success_type'=>'simple',
																'success'=>'Record has been saved successfully',
																'disposition_id'=>$disposition_id,
																'record'=>$all
																);
													echo json_encode($message);	
												
												}
										}
								}
									
									
								}
								
							break;
							
					case 'BILLING':
							
						($this->request->data['Order']['bill_fname']=='First Name') ? $this->request->data['Order']['bill_fname']='' : $bill_fname='';
						($this->request->data['Order']['bill_lname']=='Last Name') ? $this->request->data['Order']['bill_lname']='' : $bill_lname='';
						($this->request->data['Order']['bill_address1']=='Address 1') ? $this->request->data['Order']['bill_address1']='' : $bill_address1='';
						($this->request->data['Order']['bill_address2']=='Address 2') ? $this->request->data['Order']['bill_address2']='' : $bill_address2='';
						($this->request->data['Order']['bill_phone_number']=='Phone Number') ? $this->request->data['Order']['bill_phone_number']='' : $bill_phone_number='';
						($this->request->data['Order']['bill_email']=='E-mail Address') ? $this->request->data['Order']['bill_email']='' : $bill_email='';
						($this->request->data['Order']['bill_state']=='State') ? $this->request->data['Order']['bill_state']='' : $bill_state='';
						
						($this->request->data['Order']['bill_city']=='City') ? $this->request->data['Order']['bill_city']='' : $bill_city='';
						($this->request->data['Order']['bill_zip']=='Zip Code') ? $this->request->data['Order']['bill_zip']='' : $bill_zip='';
						($this->request->data['Order']['cc_number']=='CC Number') ? $this->request->data['Order']['cc_number']='' : $cc_number='';
						($this->request->data['Order']['cvv_number']=='CVV Number') ? $this->request->data['Order']['cvv_number']='' : $cvv_number='';
							
							$message=array();
							$this->Order->set($this->request->data);
							
							if(!$this->Order->billing_validation()){
								$message=array('message'=>'error',
												'error_type'=>'validation',
												'error'=>$this->Order->validationErrors);
								echo json_encode($message);	
								
							}else{
							
								if(empty($this->request->data['Order']['id'])){
									$this->request->data['Order']['id']=$this->Session->read('shipping_information.Order.id');
								}
									
								
								$data=$this->request->data;
								unset($data['Order']['SUBMIT']);
							///=======assgin shipping information  to data
								$data['Order']['ship_fname']=trim($this->Session->read('shipping_information.Order.ship_fname'));
								$data['Order']['ship_lname']=trim($this->Session->read('shipping_information.Order.ship_lname'));
								$data['Order']['ship_city']=trim($this->Session->read('shipping_information.Order.ship_city'));
								$data['Order']['ship_state']=trim($this->Session->read('shipping_information.Order.ship_state'));
								$data['Order']['ship_country']='US';
								$data['Order']['ship_address1']=trim($this->Session->read('shipping_information.Order.ship_address1'));
								$data['Order']['ship_address2']=trim($this->Session->read('shipping_information.Order.ship_address2'));
								$data['Order']['ship_phone_number']=trim($this->Session->read('shipping_information.Order.ship_phone_number'));
								$data['Order']['ship_email']=trim($this->Session->read('shipping_information.Order.ship_email'));
								$data['Order']['ship_zip']=trim($this->Session->read('shipping_information.Order.ship_zip'));
							//============== End =======================
								
								$this->Session->write('data',$data);
								
								$message=array('message'=>'success',
												'success_type'=>'simple',
												'success'=>'Record has been saved successfully');
								echo json_encode($message);die;	
								
							//==================== end ===========================
								
							}
							break;
							
					case 'VARIFICATION_2':
							$message=array();
							$this->Order->set($this->request->data['Order']);
						
							if(!$this->Order->varification_validation()){
								$message=array('message'=>'error',
												'error_type'=>'validation',
												'error'=>$this->Order->validationErrors);
								echo json_encode($message);	
								
							}else{
								$this->Order->save($this->request->data);
								
								$message=array('message'=>'success',
												'success_type'=>'simple',
												'success'=>'Record has been saved successfully');
											
								echo json_encode($message);	
							}
							break;
							
							
					case 'Default':
							
							
					default:
							break;
				
				}
			}
			
			if(isset($this->request->data['Lead']['SUBMIT']))
			{
			
			switch($this->request->data['Lead']['SUBMIT'])
			{
				
				case 'DEFAULT':
						
						($this->request->data['Lead']['first_name']=='First Name') ? $this->request->data['Lead']['first_name']='' : $test='';
						($this->request->data['Lead']['last_name']=='Last Name') ? $this->request->data['Lead']['last_name']='' : $test='';
						($this->request->data['Lead']['zip_code']=='Zip Code') ? $this->request->data['Lead']['zip_code']='' : $test='';
						
						$message=array();
						$this->Lead->set($this->request->data['Lead']);
						
						if(!$this->Lead->lead_general_validation()){
							$message=array('message'=>'error',
											'error_type'=>'validation',
											'error'=>$this->Lead->validationErrors);
							echo json_encode($message);	
							
						}else{
							$this->Lead->save($this->request->data);
							
							$message=array('message'=>'success',
											'success_type'=>'simple',
											'success'=>'Record has been saved successfully');
							echo json_encode($message);	
						}
						break;
						
				case 'VARIFICATION':
						
						if($this->request->data['Lead']['opted']=='Yes'){
							$message='The buyers has been decide to opt out from recurring payment';
						}else{
							$message='The buyers has been decide to opt in recurring payment';
						}
						
						$this->Lead->save($this->request->data);
						$message=array('message'=>'success',
											'success'=>$message);
							echo json_encode($message);	
							
						break;
						
				default:
						break;
			
			}
			}
			
			
			
			
			if(isset($this->request->data['Support']['SUBMIT']))
			{
				switch($this->request->data['Support']['SUBMIT'])
				{
					case 'SUPPORT':
						
							
							$this->Support->set($this->request->data);
						
							if(!$this->Support->support_validation()){
							
								$message=array('message'=>'error',
											'error_type'=>'validation',
											'error'=>$this->Support->validationErrors);
								echo json_encode($message);	
							
						}else{
						
							
							unset($this->request->data['Order']);
							if($this->Support->save($this->request->data['Support'],false)){
							
							($this->request->data['Support']['support_request']) ? $support_request=$this->request->data['Support']['support_request'] :  $support_request='';
							
							$sendto = EMAIL_FROM_ADDRESS;
							$sendfrom = EMAIL_FROM_ADDRESS;
						
							$subject = "Support E-mail:-";
							$bodyText = "<table>
										<tr><td>Message</td><td width='20px' align='center'>:</td>".$this->request->data['Support']['support_message']."<td></td> </tr>
										<tr><td>Support Requests</td><td width='20px' align='center'>:</td>".$support_request."<td></td> </tr>
										<tr><td>Five9 SessionID</td><td width='20px' align='center'>:</td>".$this->request->data['Support']['five9sessionid']."<td></td> </tr>
										</table>";
						
							$email = new CakeEmail();
							$email->from(array($sendfrom));
							$email->to($sendto);
							$email->emailFormat('html');
							$email->subject(strip_tags($subject));
							$ok = $email->send($bodyText);
							
							}
							$message=array('message'=>'success',
											'success_type'=>'simple',
											'success'=>'Record has been saved successfully');
							echo json_encode($message);	
						}
						break;
					default:
							
						break;
				
				}
			}
		}
		
		
	}
	
	
	function api()
	{
		$this->autoRender=false;
		$data['Lead']=$this->request->data;
		
		
		$this->Lead->set($data);
		if(!$this->Lead->api_validation()){
			$errors=$this->Lead->validationErrors;
			
			$error=array();
			foreach($errors as $key=>$value){
				$error[$key]=$value[0];
			}
			
			
			$error_msg=''; //
			$error_code='';
			foreach($error as $value){
				$message=explode('-',$value);
				$error_msg .=$message[1].'<br>';
				$error_code .=$message[0].'<br>';
			}
			
			$data1['LeadLog']['response_code']=$error_code;
			$data1['LeadLog']['response_text']=$error_msg;
			$data1['LeadLog']['request']=serialize($data['Lead']);
			$this->LeadLog->save($data1);
			
	
			
			$error=implode(',',$error);
						
			return $api_response_xml = '<?xml version="1.0" encoding="utf-8"?><response><response_message>Failure</response_message><Failure_msg>'.$error.'</Failure_msg></response>';
			
		}else{
		
		
		/*if(empty($data['Lead']['donation_amount'])){
			$data['Lead']['donation_amount']=1;
		}
		if(empty($data['Lead']['channel'])){
			$data['Lead']['donation_amount']='channel';
		}
		if(empty($data['Lead']['gender'])){
			$data['Lead']['donation_amount']='M';
		}
		
		if(empty($data['Lead']['age'])){
			$data['Lead']['age']='1';
		}
		
		if(empty($data['Lead']['annual_income'])){
			$data['Lead']['annual_income']='10';
		}
		*/
		
		
		  
		
		$rec=$this->Customer->findByusername($data['Lead']['customer_username']);
		$data['Lead']['customer_id']=$rec['Customer']['id'];
		
		$campaign_id = $data['Lead']['campaign_id'];
		   $findlist = $this->Campaign->find('first',array('fields'=>'Campaign.campaign_list_name','conditions'=>array('Campaign.id'=>$campaign_id)));
		   $listname = 'newlist';
		   if(isset($findlist['Campaign']['campaign_list_name'])) {
			$listname = $findlist['Campaign']['campaign_list_name'];
		   }
		
		
			
			if(!empty($data['Lead']['phone'])){
			
			if($this->Lead->save($data)){
			
				$data['Lead']['id']=$this->Lead->getLastInsertId();
				$data1['Lead']['id']=$data['Lead']['id'];
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
								//$data['Lead']['listname'],
								
								$res = $client->addRecordToList(array('listName'=>$data['Lead']['listname'],'listUpdateSettings'=>array('allowDataCleanup'=>FALSE, 'reportEmail'=>'4003@dothejob.org','separator'=>',', 'skipHeaderLine'=>true, 'cleanListBeforeUpdate'=>FALSE, 'crmAddMode'=>'ADD_NEW', 'crmUpdateMode'=>'UPDATE_FIRST','listAddMode'=>'ADD_FIRST', 'fieldsMapping'=>
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
																	)/*,
																	array(
																		'columnNumber'=>20,
																		'fieldName'=>'StateandTerr_US',
																		'key'=>false
																	)*/
																	
																)													
															 ),
										  'record'=>array('fields'=>array($data['Lead']['phone'],'9782341664','9782341665',$data['Lead']['first_name'],$data['Lead']['last_name'],'',$data['Lead']['address1'],$data['Lead']['city'],$data['Lead']['state'],$data['Lead']['zip_code'],$data['Lead']['address'],$data['Lead']['country'],$data['Lead']['email'],$data['Lead']['id'],$data['Lead']['channel'],$data['Lead']['age'],$data['Lead']['donation_amount'],$data['Lead']['gender'],$data['Lead']['annual_income']))
			));
					//
					
					//,$data['Lead']['StateandTerr_US']
					//,$data['Lead']['gender'],$data['Lead']['annual_income'],$data['Lead']['annual_income'],$data['Lead']['channel'],$data['Lead']['donation_amount']										
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
				
			
			$data1['LeadLog']['response_code']='';
			$data1['LeadLog']['response_text']='';
			$data1['LeadLog']['request']=serialize($data['Lead']);
			$this->LeadLog->save($data1);
				
			
			if($result=='Record has been saved sucessfully'){
					$lead_data=$this->Lead->find('first',array('conditions'=>'Lead.id="'.$data['Lead']['id'].'"'));
					$data='<campagin_name>'.$lead_data['Campaign']['name'].'</campagin_name><customer_name>'.$lead_data['Customer']['name'].'</customer_name><fname>'.$lead_data['Lead']['first_name'].'</fname><lname>'.$lead_data['Lead']['last_name'].'</lname> <email>'.$lead_data['Lead']['email'].'</email><phone>'.$lead_data['Lead']['phone'].'</phone><address>'.$lead_data['Lead']['address'].'</address><country>'.$lead_data['Lead']['country'].'</country> <state>'.$lead_data['Lead']['state'].'</state> <city>'.$lead_data['Lead']['city'].'</city><zip_code>'.$lead_data['Lead']['zip_code'].'</zip_code><version>'.$lead_data['Lead']['version'].'</version><action>'.$lead_data['Lead']['action'].'</action><custom1>'.$lead_data['Lead']['custom1'].'</custom1><custom2>'.$lead_data['Lead']['custom2'].'</custom2><custom3>'.$lead_data['Lead']['custom3'].'</custom3><custom4>'.$lead_data['Lead']['custom4'].'</custom4><custom5>'.$lead_data['Lead']['custom5'].'</custom5><ccnumber>'.$lead_data['Lead']['ccnumber'].'</ccnumber><send_reponse>'.$lead_data['Lead']['send_reponse'].'</send_reponse>';
				
					$data1['Lead']['five9_status']=1;
					$this->Lead->save($data1);
				}
				
				return $api_response_xml = '<?xml version="1.0" encoding="utf-8"?><response><response_message>Success</response_message><Success_msg>New Lead has been generated successfully.</Success_msg><Success_data>'.$data.'</Success_data></response>';
				
			}else{
				
				return $api_response_xml = '<?xml version="1.0" encoding="utf-8"?><response><response_message>Failure</response_message><Failure_msg>9000</Failure_msg></response>';
			}
			}else{
						
				return $api_response_xml = '<?xml version="1.0" encoding="utf-8"?><response><response_message>Failure</response_message><Failure_msg>9000</Failure_msg></response>';	
				}
	
		}
	
		
	
	}
	
	
	function get_lead_keywords(){
		$this->autoRender=false;
		$leadRec=$this->Lead->find('first',array('conditions'=>'Lead.id="16"'));
		pr($leadRec);
		
		$leadKeyword=$this->LeadKeyword->find('list',array('fields'=>'LeadKeyword.keyword,LeadKeyword.description'));
		pr($leadKeyword);die;
		echo json_encode($leadKeyword);
	}
	
	
	function testing()
	{
		$this->autoRender=false;
			
			
	$soapUser = 'SHM'; //Five9_username;  //  username
	$soapPassword = 'SHM'; // Five9_password; // password

	$soap_options   = array( 'login' => $soapUser, 'password' => $soapPassword );
	$auth_details   = base64_encode($soapUser.":".$soapPassword);
	
	$client = new SoapClient("https://api.five9.com/wsadmin/AdminWebService?wsdl", $soap_options);
	$header = new SoapHeader("https://api.five9.com/wsadmin/AdminWebService/runReport", "authentication", "Basic $auth_details");
	$res = '';
	$client->__setSoapHeaders($header);
	try
	{
	$res = $client->runReport(array(
			'folderName'=>'campaign_john',
			'reportName'=>'Campaign_Activity',
			'criteria'=>array(
				'time'=>array('start'=>'2011-09-23T21:00:00.000-07:00','end'=>'2013-09-23T21:00:00.000-07:00'),
				'reportObjects'=>array(
							 'objectType'=>'AgentGroup',
							 'objectNames'=>'test2'
							 )
							)
				));
	$result='Record has been saved sucessfully';
	
	}catch (Exception $e){
		$last_req = $client->__getLastRequest();
		$last_res = $client->__getLastResponse();
		$result="Error: (" . $e->getCode() . ") " . $e->getMessage();
	 }
	
	return $res->return;
	
	
	}
		
		
	function product_list($campagin_id = null){
	
		$this->autoRender=false;
		$campagin_product=$this->common->_getCampaginProduct($campagin_id);
		echo json_encode($campagin_product);
	
	}	
		
			
	function beforeFilter(){ 
		parent::beforeFilter();
		  $this->response->disableCache();
	
	}
	
	function beforeRender(){
	    parent::beforeRender();		
	}   
	
		
}
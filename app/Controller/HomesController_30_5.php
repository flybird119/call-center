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
	public $uses = array('Lead','Campaign','CallScript','Rebuttal','Form','Order','Customer','LeadKeyword','CallStartLead','Support','CallEndLead');
	public $components = array('RequestHandler','Session','common');	
		


	public function index($leadId=null){
	
		(isset($this->request->query['id'])) ? $this->set('leadId',$this->request->query['id']) : $this->set('leadId','16');
		(isset($this->request->query['campaign_name'])) ? $this->set('campagin_name',$this->request->query['campaign_name']) : $this->set('campaign_name','Campaign');
		(isset($this->request->query['user_name'])) ? $this->set('user_name',$this->request->query['user_name']) : $this->set('user_name','username');
		
		
	
		(isset($this->request->query['campaign_id'])) ? $data['CallStartLead']['campaign_id']=$this->request->query['campaign_id'] : $data['CallStartLead']['campaign_id']='';
		(isset($this->request->query['session_id'])) ? $data['CallStartLead']['session_id']=$this->request->query['session_id'] : $data['CallStartLead']['session_id']='';
		(isset($this->request->query['campaign_name'])) ? $data['CallStartLead']['campaign_name']=$this->request->query['campaign_name'] : $data['CallStartLead']['campaign_name']='';
		(isset($this->request->query['user_name'])) ? $data['CallStartLead']['user_name']=$this->request->query['user_name'] : $data['CallStartLead']['user_name']='';
		(isset($this->request->query['id'])) ? $data['CallStartLead']['lead_id']=$this->request->query['id'] : $data['CallStartLead']['lead_id']='';
		(isset($this->request->query['number'])) ? $data['CallStartLead']['number']=$this->request->query['number'] : $data['CallStartLead']['number']='';
		(isset($this->request->query['first_name'])) ? $data['CallStartLead']['first_name']=$this->request->query['first_name'] : $data['CallStartLead']['first_name']='';
		(isset($this->request->query['last_name'])) ? $data['CallStartLead']['last_name']=$this->request->query['last_name'] : $data['CallStartLead']['last_name']='';
			
			
		
	
		if(isset($this->request->query['session_id'])){
		
			$rec=$this->CallStartLead->find('first');
				
			$total_rec=$this->CallStartLead->find('count',array('conditions'=>'CallStartLead.session_id="'.$data['CallStartLead']['session_id'].'"'));
				if(!$total_rec){
					$this->CallStartLead->save($data);
				}
				
				
				
			}
	
	
	}
	
	function get_call_script($leadId=null){
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
		
		
		
	/*	
		$this->CallScript->id=$callScriptId;
		$campaignId=$this->CallScript->field('CallScript.campaign_id');
		$keyList=$this->common->get_campagin_keyowrd_key_list($campaignId);
		$valueList=$this->common->get_campagin_keyowrd_value_list($campaignId);*/
		//pr($rebutal_list);die;
		
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
	
	function get_form_html($form_id=null,$lead_id=null)
	{
	
		$lead_id=$lead_id;
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
					$lead_id=$lead_id;
					$this->request->data=$this->Order->find('first',array('conditions'=>'Order.lead_id="'.$lead_id.'"'));
					
					$this->render($ctp_name);
					break;
					
				case 'billing.ctp':
					
					$this->Lead->id=$lead_id;
					$customer_id=$this->Lead->field('customer_id');
					$this->set('customer_id',$customer_id);
					
					$this->request->data=$this->Order->find('first',array('conditions'=>'Order.lead_id="'.$lead_id.'"'));
					$this->request->data['Order']['cc_number']='';
					$this->request->data['Order']['cc_expiration_month']='';
					$this->request->data['Order']['cc_expiration_year']='';
					$this->request->data['Order']['cvv_number']='';
					
					
					$this->render($ctp_name);
					break;
					
				case 'varification.ctp':
					$lead_id=$lead_id;
					$this->set(compact('lead_id'));
					
					$this->Lead->id=$lead_id;
					$this->request->data=$this->Lead->find('first',array('conditions'=>'Lead.id="'.$lead_id.'"'));
					
					
					$this->render($ctp_name);
					break;
						
				case 'default.ctp':
					$lead_id=$lead_id;
					$this->request->data=$this->Lead->find('first',array('conditions'=>'Lead.id="'.$lead_id.'"','recursive'=>-1));
					
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
								
								$this->Session->Write('shipping_information',$this->request->data);
								
								$this->Order->save($this->request->data);
								
								if(empty($this->request->data['Order']['id'])){
									$this->request->data['Order']['id']=$this->Order->getLastInsertId();
								}
								$this->Session->Write('shipping_information',$this->request->data);
								
								
								$message=array('message'=>'success',
												'success_type'=>'simple',
												'success'=>'Record has been saved successfully');
								echo json_encode($message);	
							}
						
							break;
							
					case 'BILLING':
							
						($this->request->data['Order']['bill_fname']=='First Name') ? $this->request->data['Order']['bill_fname']='' : $test='';
						($this->request->data['Order']['bill_lname']=='Last Name') ? $this->request->data['Order']['bill_lname']='' : $test='';
						($this->request->data['Order']['bill_address1']=='Address 1') ? $this->request->data['Order']['bill_address1']='' : $test='';
						($this->request->data['Order']['bill_address2']=='Address 2') ? $this->request->data['Order']['bill_address2']='' : $test='';
						($this->request->data['Order']['bill_phone_number']=='Phone Number') ? $this->request->data['Order']['bill_phone_number']='' : $test='';
						($this->request->data['Order']['bill_email']=='E-mail Address') ? $this->request->data['Order']['bill_email']='' : $test='';
						($this->request->data['Order']['bill_state']=='State') ? $this->request->data['Order']['bill_state']='' : $test='';
						
						($this->request->data['Order']['bill_city']=='City') ? $this->request->data['Order']['bill_city']='' : $test='';
						($this->request->data['Order']['bill_zip']=='Zip Code') ? $this->request->data['Order']['bill_zip']='' : $test='';
						($this->request->data['Order']['cc_number']=='CC Number') ? $this->request->data['Order']['cc_number']='' : $test='';
						($this->request->data['Order']['cvv_number']=='CVV Number') ? $this->request->data['Order']['cvv_number']='' : $test='';
							
							$message=array();
							$this->Order->set($this->request->data);
							
							if(!$this->Order->billing_validation()){
								$message=array('message'=>'error',
												'error_type'=>'validation',
												'error'=>$this->Order->validationErrors);
								echo json_encode($message);	
								
							}else{
							
								$this->request->data['Order']['id'];
								if(empty($this->request->data['Order']['id'])){
									$this->request->data['Order']['id']=$this->Session->read('shipping_information.Order.id');
								}
									
								
						//================= code for payment =================
							$sfirstname=trim($this->Session->read('shipping_information.Order.ship_fname'));  //=== shipping detail ====
							$slastname=trim($this->Session->read('shipping_information.Order.ship_lname'));
							$scity=trim($this->Session->read('shipping_information.Order.ship_city'));
							$sstate=trim($this->Session->read('shipping_information.Order.ship_state'));
							$scountry='USA';
							$saddress1=trim($this->Session->read('shipping_information.Order.ship_address1'));
							$saddress2=trim($this->Session->read('shipping_information.Order.ship_address2'));
							$szipcode=trim($this->Session->read('shipping_information.Order.ship_zip'));
							$phonenumber=trim($this->Session->read('shipping_information.Order.phonenumber'));
							
							$cardnumber=trim($this->request->data['Order']['cc_number']);  //==== card deail ====
							$cardcvv=trim($this->request->data['Order']['cvv_number']);
							$cardtype=trim($this->request->data['Order']['cc_card_type']);
							$expirymonth=trim($this->request->data['Order']['cc_expiration_month']);
							$expiryYear=trim($this->request->data['Order']['cc_expiration_year']);
							$product1_name=trim('Testing');
							$product1_qty=1;
							$product1_totalprice=0.01;
							
							
							$bfirstname=trim($this->request->data['Order']['bill_fname']); // === billing detail ==
							$blastname=trim($this->request->data['Order']['bill_lname']);
							$baddress1=trim($this->request->data['Order']['bill_address1']);
							$baddress2=trim($this->request->data['Order']['bill_address2']);
							$bcity=trim($this->request->data['Order']['bill_city']);
							$bstate=trim($this->request->data['Order']['bill_state']);
							$bzipcode=trim($this->request->data['Order']['bill_zip']);
							$bcountry='USA';
							
							
							
							($this->request->data['Order']['customer_id']) ? $cust_id=$this->request->data['Order']['customer_id'] : $cust_id='';
								
								if(!empty($cust_id)){
								$this->Customer->id=$cust_id;
								$paymentgatway=$this->Customer->field('paymentgetway');  // select payment getway
								
									if(isset($paymentgatway)){
											switch($paymentgatway){
													case 'Merchant Service Corp eReports Payment':
														/*	$url='https://gateway.sslapplications.net/ynMklop/?type=sale&token=8E977F93-27D8-45BE-8B2D-%2000DAE781D488&storeid=15&offer=Default&saleorigin=cebuglobaltel.com&bsameasshipping=N&sfirstname='.$sfirstname.'&slastname='.$slastname.'&scity='.$scity.'&sstate='.$sstate.'&scountry=USA&saddress1='.$saddress1.'&szipcode='.$szipcode.'&szipcode='.$szipcode.'&bfirstname='.$bfirstname.'&blastname='.$blastname.'&baddress1='.$baddress1.'&baddress2='.$baddress2.'&bcity='.$bcity.'&bstate='.$bstate.'&bzipcode='.$bzipcode.'&bcountry='.$bcountry.'&cardnumber='.$cardnumber.'&expirymonth='.$expirymonth.'&expiryYear='.$expiryYear.'&pid=403&product1_name=Ld3fejq&product1_qty=9&product1_totalprice=0.01&test=1';
															*/
															
												
								$url = 'https://gateway.sslapplications.net/ynMklop/?' . http_build_query(array(
																											'type' => 'sale',
																											'token' => '433ca874-ccf0-473d-9d58-981f552709d4',
																											'storeid' => '363',
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
																											'pid' => '403',
																											'product1_name' =>'product1',
																											'product1_qty' => 1,
																											
																											'product1_totalprice' =>  0.01,
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
													case 'Mediahub':
														
														$fields = array(
																		 'IPAddress'=>$_SERVER["REMOTE_ADDR"],
																		 'CampaignID'=>'127',
																		 'Upsale2ID'=>'128',
																		'Upsale1ID'=>'128',
																		'PublisherID'=>'10',
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
																		'apikey'=>'2b899dea7fa654634dca0eb0d0411d83a4e99d64',
																		'token'=>'c9e0c74b5f98acfdf258b46213437769'
																		);
																		
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
														
													default:
														break;
												}
												
												
													
													
										
											if($payment_response['success']=='Yes'){ // if payment done 
												
													$string=$this->request->data['Order']['cc_number'];
													$ccsub_string=substr($string,4,9);
	
													$this->request->data['Order']['cc_number']=str_replace($ccsub_string,'*********',$string);
										
													$string=$this->request->data['Order']['cc_number'];
													$ccsub_string=substr($string,4,9);
													$this->request->data['Order']['cc_number']=str_replace($ccsub_string,'*********',$string);
													$this->request->data['Order']['cc_expiration_month']='';
													$this->request->data['Order']['cc_expiration_year']='';
													$this->request->data['Order']['cvv_number']='';
										
										
										
													
											
													if($this->Order->save($this->request->data,false)){
													
													$this->Order->id=$this->request->data['Order']['id'];
													$all=$this->Order->findAllById($this->Order->id);
													
													
													$message=array('message'=>'success',
																'success_type'=>'payment',
																'success'=>$payment_response['message'],
																'record'=>$all
																);
													echo json_encode($message);	
												
												}
												}else{
													$message=array('message'=>'error',
																	'error_type'=>'payment',
																	'error'=>$payment_response['message']
																);
													echo json_encode($message);		
												}
										
										}else{
											if($this->Order->save($this->request->data)){
													$this->Order->id=$this->request->data['Order']['id'];
													$all=$this->Order->findAllById($this->Order->id);
													$message=array('message'=>'success',
																'success_type'=>'simple',
																'success'=>'Record has been saved successfully',
																'record'=>$all
																);
													echo json_encode($message);	
												
												}
										}
								}
							
							
							//==================== end ===========================
								
							}
							break;
							
					case 'VARIFICATION':
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
							$this->Support->save($this->request->data['Support'],false);
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
		/*$data['Lead']['customer_username']='';
		$data['Lead']['customer_password']='';
		$data['Lead']['phone']='';
		$data['Lead']['zip_code']='';
		
		
		
		
		$error=print_r($data);
		$this->Lead->set($data);
						
		if(!$this->Lead->api_validation()){
			$errors=$this->Lead->validationErrors;
			$error=array();
			foreach($errors as $key=>$value){
				$error[$key]=$value[0];
			}
			
		}else{
			//$error='no';
		}*/
			
			
			return $api_response_xml = '<?xml version="1.0" encoding="utf-8"?><response><response_message>Success</response_message><Success_msg>New Lead has been generated successfully.</Success_msg><Success_data>'.$error.'</Success_data></response>';
		
		die;
		$rec=$this->Customer->findByusername($data['Lead']['customer_username']);
		$data['Lead']['customer_id']=$rec['Customer']['id'];
		
		$campaginRec=$this->Campaign->find('first',array('fields'=>'Campaign.id','conditions'=>'Campaign.product_id="'.$data['Lead']['product_id'].'"','recursive'=>-1));
		$campaginFound=$this->Campaign->find('count',array('conditions'=>'Campaign.product_id="'.$data['Lead']['product_id'].'"'));
		$customerFound=$this->Customer->find('count',array('conditions'=>'Customer.username="'.$data['Lead']['customer_username'].'" and Customer.password="'.$data['Lead']['customer_password'].'" '));
		
		$data['Lead']['campaign_id']=$campaginRec['Campaign']['id'];
		
		
		if(empty($data['Lead']['product_id'])){
			
			return $api_response_xml = '<?xml version="1.0" encoding="utf-8"?><response><response_message>Failure</response_message><Failure_msg>Please enter product id.</Failure_msg></response>';
			
		}if(empty($data['Lead']['customer_username'])){
			return $api_response_xml = '<?xml version="1.0" encoding="utf-8"?><response><response_message>Failure</response_message><Failure_msg>Please enter customer username.</Failure_msg></response>';
		}elseif(empty($data['Lead']['customer_password'])){
			return $api_response_xml = '<?xml version="1.0" encoding="utf-8"?><response><response_message>Failure</response_message><Failure_msg>Please enter customer password.</Failure_msg></response>';
		}elseif(empty($campaginFound)){
			
			return $api_response_xml = '<?xml version="1.0" encoding="utf-8"?><response><response_message>Failure</response_message><Failure_msg>Campagin not exits for given product and customer username and customer password.</Failure_msg></response>';
			
			
		}elseif(empty($customerFound)){
			return $api_response_xml = '<?xml version="1.0" encoding="utf-8"?><response><response_message>Failure</response_message><Failure_msg>Customer not exits for given product and customer username and customer password.</Failure_msg></response>';
			
		}else{
			
			
	
			if($this->Lead->save($data)){
			
				
				$data['Lead']['id']=$this->Lead->getLastInsertId();
				$data1['Lead']['id']=$data['Lead']['id'];
				
				
				$data['Lead']['listname']='newlist';
				
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
																		'fieldName'=>'id',
																		'key'=>false
																	)
																)													
															 ),
										  'record'=>array('fields'=>array($data['Lead']['phone'],'9782341664','9782341665',$data['Lead']['first_name'],$data['Lead']['last_name'],'','','','',$data['Lead']['zip_code'],$data['Lead']['id']))
										 
										
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
					
					$lead_data=$this->Lead->find('first',array('conditions'=>'Lead.id="'.$data['Lead']['id'].'"'));
					
									
					$data='<campagin_name>'.$lead_data['Campaign']['name'].'</campagin_name><customer_name>'.$lead_data['Customer']['name'].'</customer_name><fname>'.$lead_data['Lead']['first_name'].'</fname><lname>'.$lead_data['Lead']['last_name'].'</lname> <email>'.$lead_data['Lead']['email'].'</email><phone>'.$lead_data['Lead']['phone'].'</phone><address>'.$lead_data['Lead']['address'].'</address><country>'.$lead_data['Lead']['country'].'</country> <state>'.$lead_data['Lead']['state'].'</state> <city>'.$lead_data['Lead']['city'].'</city><zip_code>'.$lead_data['Lead']['zip_code'].'</zip_code><version>'.$lead_data['Lead']['version'].'</version><action>'.$lead_data['Lead']['action'].'</action><custom1>'.$lead_data['Lead']['custom1'].'</custom1><custom2>'.$lead_data['Lead']['custom2'].'</custom2><custom3>'.$lead_data['Lead']['custom3'].'</custom3><custom4>'.$lead_data['Lead']['custom4'].'</custom4><custom5>'.$lead_data['Lead']['custom5'].'</custom5><ccnumber>'.$lead_data['Lead']['ccnumber'].'</ccnumber><send_reponse>'.$lead_data['Lead']['send_reponse'].'</send_reponse>';
				
					$data1['Lead']['five9_status']=1;
					$this->Lead->save($data1);
					
				}
				
				
				
				//$this->common->add_to_record($data);
				
				return $api_response_xml = '<?xml version="1.0" encoding="utf-8"?><response><response_message>Success</response_message><Success_msg>New Lead has been generated successfully.</Success_msg><Success_data>'.$data.'</Success_data></response>';
				
			}else{
				
				return $api_response_xml = '<?xml version="1.0" encoding="utf-8"?><response><response_message>Failure</response_message><Failure_msg>Please provide all field values correctly.</Failure_msg></response>';
			}
		}
		
		
		
		
		if(!empty($campaginFound) and !empty($customerFound)){
			
			
		}else{
			
			
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
			
	function beforeFilter(){ 
		parent::beforeFilter();
		  $this->response->disableCache();
	
	}
	
	function beforeRender(){
	    parent::beforeRender();		
	}   
	
		
}
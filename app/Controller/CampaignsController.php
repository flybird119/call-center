<?php
/* -----------------------------------------------------------------------------------------
   Call centre project - http://planetwebsolution.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2013 planetwebsolution Ltd.
  
   ---------------------------------------------------------------------------------------*/

class CampaignsController extends AppController {
	//public $helpers = array('TinyMce');
	public $name = 'Campaigns';
    public $theme = 'Admin';
	public $uses = array('Customer','Campaign','Keyword','CallScript','Rebuttal','LeadKeyword','CampaignProduct','CampaignUpsale');
    public $helpers = array('Js','Html','Javascript','Text','Paginator','Ajax');    
        

	/**
	* Customer listing
	*
	*/
	
	public function campaignslist()
	{
	
		if($this->request->isPost())
		{
		
		// enter in search condidtions
		$campaign_name = $this->request->data['campaign_name'];
		$customer_name = $this->request->data['customer_name'];
			 
		
		 $conditions ='';
			 if(!empty($campaign_name))
			 {
				$conditions.='Campaign.name like "%'.$campaign_name.'%" ';	 
			 }
			 
			if(!empty($customer_name))
			 {	
			 	if(!empty($conditions)) $conditions.=' or ';
				$conditions.='Customer.name like "%'.$customer_name.'%"';	 
			 }
		


		
	
		
		$this->paginate	= array('conditions'=>$conditions,'order'=>array('Campaign.name desc'),'limit'=>PAGE_RECORD,'recursive'=>0);
		
		$this->set('CampaignList', $this->paginate('Campaign'));
		
		$this->set('totalRecords', $this->Campaign->find('count',array('conditions'=>$conditions)));	
		}
		else
		 {
		 	 
			 
			 $this->Campaign->virtualFields['fc_ids']='Campaign.five9_campaign_id';
			 $this->paginate	= array('order'=>'Campaign.name desc','limit'=>PAGE_RECORD,'recursive'=>0); 
		  	 $CampaignList =$this->paginate('Campaign');
			 $this->set('CampaignList', $CampaignList);
			 $this->set('totalRecords', $this->Campaign->find('count'));	
		 }
		
		// pr($CampaignList);die;
		 
		
	}
	
	
	
	/**
	* function used to edit campaign
	*
	*/
	
	public function editcampaign($campaignId = null)
	{
		$this->set('customers',$this->Customer->find('list'));
		$this->set('existingEampaigns',$this->Campaign->find('list'));
		
		$this->set('campaignId',$campaignId);
	
		$this->paginate	= array('conditions'=>array('Keyword.campaign_id'=>$campaignId),'order'=>'Keyword.keyword desc','limit'=>'50'); 
		 $keywordList =$this->paginate('Keyword');
		 $this->set('keywordList', $keywordList);
		 
		 	$arrayAction = array();
			$arrayAction['action']='getListsInfo';    // get five9 lead list
			$five9List=$this->common->five9_api($arrayAction);
			$this->set('five9List',$five9List);
		 
		
		if(isset($this->request->query['editkeyword']))
		{
		$editkeywordId = $this->request->query['editkeyword'];
		$this->set('editkeywordId', $editkeywordId);
		$keywords = $this->Keyword->find('first',array('conditions'=>array('Keyword.id'=>$editkeywordId)));
		$keywordData = $keywords['Keyword'];
		}
	
		if($this->request->is('post'))  // new mnenu entry
			{ 
				// edit Campaign condition 
				if($this->request->data['Campaign']['form_type']=='Campaignform')	
				{
					$success = __('Campaign has been edited successfully');
					$error = '<span style="color:red">'.__('Please fill all mendatory fields').'</span>';
			
					$this->Campaign->set($this->request->data);
					if(!$this->Campaign->campaignValidation()) // checking validation
					{
						$errorsArr = $this->Campaign->validationErrors;
					}
					else
					{ 	
					
				
						$this->Campaign->save($this->request->data);
						
						$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');			
						$this->redirect(array('controller'=>'campaigns','action'=>'campaignslist'));
					}
				}
				// edit Campaign condition end 
				
				// add keyword condition 
				if($this->request->data['Campaign']['form_type']=='keywordform')	
				{ 
					$success = __('Keyword has been add successfully');
					$error = '<span style="color:red">'.__('Please fill all mendatory fields').'</span>';
					
					$this->Keyword->set($this->request->data);
					if(!$this->Keyword->keywordValidation()) // checking validation
					{
						$errorsArr = $this->Keyword->validationErrors;
					}
					else
					{ 	
						$this->Keyword->save($this->request->data);
						$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');			
						$this->redirect($this->referer());
					}
					
					$keywordData = $this->request->data['Keyword'];
					
				}
				// add keyword condition  End
				
				// Edit keyword condition 
				if($this->request->data['Campaign']['form_type']=='keywordformEdit')	
				{ 
					$success = __('Keyword has been edited successfully');
					$error = '<span style="color:red">'.__('Please fill all mendatory fields').'</span>';
					
					$this->Keyword->set($this->request->data);
					if(!$this->Keyword->editkeywordValidation()) // checking validation
					{
						$errorsArr = $this->Keyword->validationErrors;
					}
					else
					{ 	
						$this->Keyword->save($this->request->data);
						$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');			
						$this->redirect(array('controller'=>'campaigns','action'=>'editcampaign/'.$campaignId));
					}
					
					$keywordData = $this->request->data['Keyword'];
					
				}
				// Edit keyword condition  End
				
					
			}else{
			
			 	$this->request->data = $this->Campaign->find('first',array('conditions'=>array('Campaign.id'=>$campaignId))); // set data for edit 
			
				if(isset($keywordData))	
				{
					$this->request->data['Keyword'] = $keywordData;
				}
				}
		
				//$this->request->data['Campaign']['start_date'] = date("Y-m-d",strtotime($this->request->data['Campaign']['start_date']));
				//$this->request->data['Campaign']['end_date'] = date("Y-m-d",strtotime($this->request->data['Campaign']['end_date']));
		
		
	}
	
	/**
	* function used to add customer
	*
	*/
	
	public function addcampaign()
	{
		
		
		$success = __('Campaign has been added successfully');
		$error = '<span style="color:red">'.__('Please fill all mendatory fields').'</span>';
		
		$this->set('customers',$this->Customer->find('list'));
		$this->set('existingEampaigns',$this->Campaign->find('list'));
		
		if($this->request->is('post'))  // new mnenu entry
			{ 
					$this->Campaign->set($this->request->data);
				
					if(!$this->Campaign->campaignValidation()) // checking validation
					{
						$errorsArr = $this->Campaign->validationErrors;
					}else
					{  
						$this->request->data['Campaign']['active'] =1;
						//$this->request->data['Campaign']['start_date'] = date("Y-m-d h:m:i",strtotime($this->request->data['Campaign']['start_date']));
						//$this->request->data['Campaign']['end_date'] = date("Y-m-d h:m:i",strtotime($this->request->data['Campaign']['end_date']));
						
						$this->Campaign->save($this->request->data);
						$campaignId = $this->Campaign->getLastInsertId();
				
				
						// add five call script
						
			
						 // copy code if admin select any existing campaign		
						if($this->request->data['Campaign']['copy_campaign']==1)
						{
							$this->Campaign->recursive = 2;
							$existingCampaign = $this->Campaign->findById($this->request->data['Campaign']['existing_campaign']);
							
							// keyword copy code End
							foreach($existingCampaign['Keyword'] as $keywords)
							{
								$this->Keyword->create(); // Create a new record
								$keywords['id'] ='';
								$keywords['campaign_id'] = $campaignId;
								$this->Keyword->save($keywords,false); // And save it
							}
							// keyword copy code End
							
							
							// Call Script and Rebuttal copy code  start
							foreach($existingCampaign['CallScript'] as $callScript)
							{
								
								$this->CallScript->create(); // Create a new record
								$callScript['id'] ='';
								$callScript['campaign_id'] = $campaignId;
								$this->CallScript->save($callScript,false); // And save it
								$callScriptId = $this->CallScript->getLastInsertId();
								
								foreach($callScript['Rebuttal'] as $rebuttal)
								{
									$this->Rebuttal->create(); // Create a new record
									$rebuttal['id'] ='';
									$rebuttal['call_script_id'] = $callScriptId;
									$this->Rebuttal->save($rebuttal,false); // And save it
								}
							}
							// Call Script and Rebuttal copy code  start End
							
							
							
						}	
						
						$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');			
						$this->redirect(array('controller'=>'campaigns','action'=>'campaignslist'));
					}
			}
			
			$arrayAction = array();
			$arrayAction['action']='getListsInfo';    // get five9 lead list
			$five9List=$this->common->five9_api($arrayAction);
			$this->set('five9List',$five9List);
		
		
		
	}
	
		/**
	 * Used to delete the customer by Admin
	 *
	 * @access public
	 * @return void
	 */
	public function deleteCampaign($campaignId = null) {
		$success = __('Campaign is deleted successfully');
		$error = __('You cannot delete this campaign');
		
		
		$this->Campaign->delete($campaignId,true);
		$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');			
		$this->redirect(array('controller'=>'campaigns','action'=>'campaignslist'));
	}
	
	
	/**
	 * Used to delete the Keyword by Admin
	 *
	 * @access public
	 * @return void
	 */
	public function deleteKeyword($keywordId = null) {
		$success = __('Keyword is deleted successfully');
		$error = __('You cannot delete this campaign');
		
		
		$this->Keyword->delete($keywordId,false);
		$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');			
		$this->redirect($this->referer());
	}
	
	/**
	 * Used to change status call script
	 *
	 * @access public
	 * @return void
	 */
	 public function changestatus($id = null){
		
		$this->loadModel('CallScript');            
		
			   if(!empty($this->request->data))
			   {
					$CallData = $this->CallScript->find('first',array('conditions'=>array('CallScript.id'=>$this->request->data['id'])));
					if($CallData['CallScript']['active']==1)
					{
						$this->request->data['CallScript']['id'] = $this->request->data['id'];
						$this->request->data['CallScript']['active'] = 0;
						$this->CallScript->save($this->request->data,false);
						echo 0;exit;
					}
					if($CallData['CallScript']['active']==0)
					{
						$this->request->data['CallScript']['id'] = $this->request->data['id'];
						$this->request->data['CallScript']['active'] = 1;
						$this->CallScript->save($this->request->data,false);
						echo 1;exit;
					}
				
				   
			   }
		
		
        }
		
	/**
	 * Used to create copy call script
	 *
	 * @access public
	 * @return void
	 */
	 public function capyCallScript($callScriptId = null){	
	 
		$this->loadModel('CallScript'); 
		$success = __('Call Script copied successfully');
		$error = __('You cannot delete this campaign');
		
	 	if(isset($callScriptId))
		{
			$row = $this->CallScript->find('first',array('conditions'=>'CallScript.id="'.$callScriptId.'"',
														'hiddenField' => false,
														'contain' => false 
														));
			
			
			$this->CallScript->create(); // Create a new record
			$row['CallScript']['id'] ='';
			
			
			foreach($row['Rebuttal'] as $key=>$value){
				$row['Rebuttal'][$key]['id']='';
			}
			
			
			$this->CallScript->saveAll($row); // And save it
			
			$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');			
			$this->redirect($this->referer());
			
					
		}
		
	//	CallScript.campaign_id,CallScript.form_id,CallScript.name,CallScript.script,CallScript.active,Rebuttal.call_script_id,Rebuttal.objection,Rebuttal.rebuttals,Rebuttal.active
					
					
	 }
	 
	 /**
	 * Used to delete the Call Script by Admin
	 *
	 * @access public
	 * @return void
	 */
	public function deleteCallScript($callScriptId = null) {
		$success = __('Call Script is deleted successfully');
		$error = __('You cannot delete this campaign');
		
		$this->loadModel('CallScript'); 
		
	//	pr($this->CallScript->findById($callScriptId));die;
		
	//	$this->CallScript->delete($callScriptId,false);
		$this->CallScript->deleteAll(array('CallScript.id' => $callScriptId ));
		$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');			
		$this->redirect($this->referer());
	}
	
	/**
	 * Used to add Call Script by Admin
	 *
	 * @access public
	 * @return void
	 */
	public function addCallScript($campaignId = null){
		$this->id=$campaignId;
		$this->set(compact('campaignId'));
		$data['CallScript']['campaign_id']=$campaignId;
		$data['CallScript']['active']=1;
		$this->CallScript->save($data);
		$scriptId=$this->CallScript->getLastInsertId();
		$this->redirect(array('controller'=>'campaigns','action'=>'edit_call_script/'.$campaignId.'/'.$scriptId));
		
		die;
		
		$form_list=$this->common->get_form_list();
		$this->set(compact('form_list'));
		//$this->CallScript->save();

		$success = __('Call Script has beed added successfully');
		$error = __('error');
			
		if ($this->request->data) {
		
			if(isset($this->request->data['CallScript']['Submit'])){
				$this->CallScript->set($this->request->data['CallScript']);
				if(!$this->CallScript->callscript_validation()){
					$this->CallScript->validationErrors;
				}else{
					//$this->CallScript->save($this->reqiest->data);
					$this->CallScript->save($this->request->data);
					$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');			
					$this->redirect($this->referer());
				}
			}
			
		}	
		
			if ($this->request -> isGet()) {
				$this->id=$campaignId;
				$this->set(compact('campaignId'));
			}
			
		
	}	
	
	/**
	 * Used to edit Call Script by Admin
	 *
	 * @access public
	 * @return void
	 */
	public function edit_call_script($campaignId = null,$scriptId = null) {
		$this->set(compact('campaignId'));
		$this->set(compact('scriptId'));
		
		//============= get leads keyword list =============
			$leadKeyword=$this->LeadKeyword->find('all',array('fields'=>'LeadKeyword.keyword,LeadKeyword.description'));
			$this->set(compact('leadKeyword'));
		
		
		$rebuttals_list=$this->Rebuttal->find('first',array()); // get rebuttals list
		
		
		$this->paginate	= array('conditions'=>'Rebuttal.call_script_id="'.$scriptId.'"','limit'=>PAGE_RECORD);
		$rebuttals_list=$this->paginate('Rebuttal');
		$this->set('rebuttals_list',$rebuttals_list);
		$this->set('totalRecords',$this->Rebuttal->find('count',array('conditions'=>'Rebuttal.call_script_id="'.$scriptId.'"')));		
		
		
	
		$form_list=$this->common->get_form_list();
		$this->set(compact('form_list'));
		//$this->CallScript->save();

		$success = __('Call Script has beed added successfully');
		$error = __('error');
			
		if ($this->request->data) {
			if(isset($this->request->data['CallScript']['Submit'])){
				$this->CallScript->set($this->request->data['CallScript']);
				if(!$this->CallScript->callscript_validation()){
					$this->CallScript->validationErrors;
				}else{
					$this->CallScript->save($this->request->data);
					$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');			
					$this->redirect($this->referer());
				}
			}
		
		}else{
		
			$this->request->data=$this->CallScript->find('first',array('conditions'=>'CallScript.id="'.$scriptId.'"'));
			
		}
		
			if ($this->request -> isGet()) {
				$this->id=$campaignId;
				$this->set(compact('campaignId'));
			}

	}
	
	function add_rebuttal($call_script_id = null){
		
	
		$this->set('call_script_id',$call_script_id);
	//	$data=$this->Rebuttal->find('first',array('conditions'=>'Rebuttal.id="'.$rebuttal_id.'"'));
		
	//	$this->request->data=$data;
	//	$this->request->data['Rebuttal']['call_script_id']=$call_script_id;
		
	}
	
	function edit_rebuttal($call_script_id = null,$rebuttal_id = null){
		
	
		$data=$this->Rebuttal->find('first',array('conditions'=>'Rebuttal.id="'.$rebuttal_id.'"'));
		
		$this->request->data=$data;
		$this->request->data['Rebuttal']['call_script_id']=$call_script_id;
		
	}	
	function formsubmit()
	{
		$this->autoRender=false;
	//	pr($this->request->data);die;
		$this->Rebuttal->save($this->request->data);
		echo 'Rebuttal has been saved successfully';
		die;
	}
	
	 /**
	 * Used to  copy Rebuttal 
	 *
	 * @access public
	 * @return void
	 */
	 public function copyRebuttal($rebuttalId = null){	
	 
		$this->loadModel('Rebuttal'); 
		$success = __('Rebuttal copied successfully');
		$error = __('You cannot delete this rebuttal');
		
	 	if(isset($rebuttalId))
		{
			$row = $this->Rebuttal->findById($rebuttalId);
			$this->Rebuttal->create(); // Create a new record
			$row['Rebuttal']['id'] ='';
			$this->Rebuttal->save($row,false); // And save it
			$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');			
			$this->redirect($this->referer());
					
		}
		
	
	 }
	 
	 /**
	 * Used to Delete Rebuttal 
	 *
	 * @access public
	 * @return void
	 */
	 
	 public function deleteRebuttal($rebuttalId = null) {
		$success = __('Rebuttal is deleted successfully');
		$error = __('You cannot delete this campaign');
		
		$this->loadModel('Rebuttal'); 
		
		$this->Rebuttal->delete($rebuttalId,false);
		$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');			
		$this->redirect($this->referer());
	}
	
	function exportAll()
	{  
		$this->autoRender = false;
    	if($this->request->is('post'))  // new job entry
			{ 
				$conditions = '';
				if(!empty($this->request->data['customers']['start_date']))
				{
					$start_date = $this->request->data['customers']['start_date'];
				}
				if(!empty($this->request->data['customers']['end_date']))
				{
					$end_date = $this->request->data['customers']['end_date'];
				}
			
					// condition for if  start dat grater then end date
				if(isset($start_date) &&  isset($end_date))
				{
					if($start_date > $end_date)
					{
						$this->Session->setFlash("start date can\'t greater then end date", 'Dialogs/top_right', array('type'=>'error'), 'top_right');
						$this->redirect(array('controller'=>'customers','action'=>'customerslist'));
						
					}
					
				}
				
				// condition for if  start dat equal end date
				if(!empty($start_date) && !empty($end_date) && ($start_date==$end_date))
				{
					$start_date = date("Y-m-d",strtotime($start_date));
					if(!empty($conditions)) $conditions.=' AND ';
					$conditions.='Customer.created like "%'.$start_date.'%"';
					$results = $this->Contact->find('all',array('conditions'=>$conditions));
				}
				else if(isset($start_date) || isset($end_date))
				{
					
					 if(!empty($start_date))
						{	
						   $start_date = date("Y-m-d h:m:s",strtotime($start_date));
						   if(!empty($conditions)) $conditions.=' AND ';
						   $conditions.='Customer.created >= "'.$start_date.'"';	 
						}
						if(!empty($end_date))
						{	
							$end_date = date("Y-m-d h:m:s",strtotime($end_date));
							if(!empty($conditions)) $conditions.=' AND ';
							$conditions.='Customer.created <= "'.$end_date.'"';	 
						}
				
					$results = $this->Customer->find('all',array('conditions'=>$conditions));
					
				}
				else{
							$results = $this->Customer->find('all');
						
					}	
								ini_set('max_execution_time', 600); //increase max_execution_time to 10 min if data set is very large
		
								//create a file
								$filename = "customer_list_".date("Y.m.d").".csv";
								$csv_file = fopen('php://output', 'w');
								
								header('Content-type: application/csv');
								header('Content-Disposition: attachment; filename="'.$filename.'"');
						
						
								// The column headings of your .csv file
								$header_row = array("name","email","website_url","created");
								fputcsv($csv_file,$header_row,',','"');
							
								// Each iteration of this while loop will be a row in your .csv file where each field corresponds to the heading of the column
								foreach($results as $result)
								{ // Array indexes correspond to the field names in your db table(s)
									$row = array(
										
										$result['Customer']['name'],
										$result['Customer']['email'],
										$result['Customer']['website_url'],
										$result['Customer']['created']
										
									);
									
									fputcsv($csv_file,$row,',','"');
								}
								
								fclose($csv_file);die;
		}
		
	}
	
	
	function edit_product($campaign_id=null,$product_id=null)
	{  
		$this->CampaignProduct->id=$product_id;
	
		$success = __('Product has been edited successfully');
		$error = '<span style="color:red">'.__('Please fill all mendatory fields').'</span>';
		$this->set('campaign_id',$campaign_id);
		$this->set('product_id',$product_id);
		
		if($this->request->isPost()){
				$this->CampaignProduct->Set($this->request->data);
				
				
				if(!$this->CampaignProduct->product_Validation()) // checking validation
				{
					$errorsArr = $this->CampaignProduct->validationErrors;
				}else
				{ 
					$data=$this->request->data;
					
					if($this->CampaignProduct->save($data,true)){
						$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');	
						//$this->redirect('/editcampaign/'.$data['CampaignProduct']['campaign_id']);
						$this->redirect(array('controller'=>'campaigns','action'=>'editcampaign',$data['CampaignProduct']['campaign_id']));
						
						
					}else{
						$this->Session->setFlash($error, 'Dialogs/top_right', array('type'=>'success'), 'top_right');	
							$this->redirect(array('controller'=>'campaigns','action'=>'editcampaign',$data['CampaignProduct']['campaign_id']));
					}
				}
				
		}else{
		
				$this->request->data = $this->CampaignProduct->find('first',array('conditions'=>array('CampaignProduct.id'=>$product_id))); // set data for edit 
		}
	}
	
	function add_product($campaign_id=null)
	{  
		$this->set('campaign_id',$campaign_id);
		$success = __('Product has been added successfully');
		$error = '<span style="color:red">'.__('Please fill all mendatory fields').'</span>';
			
		if($this->request->isPost()){
				$this->CampaignProduct->Set($this->request->data);
				
				
				if(!$this->CampaignProduct->product_Validation()) // checking validation
				{
					$errorsArr = $this->CampaignProduct->validationErrors;
				}else
				{ 
					$data=$this->request->data;
					if($this->CampaignProduct->save($data,true)){
						$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');	
						//$this->redirect('/editcampaign/'.$data['CampaignProduct']['campaign_id']);
						$this->redirect(array('controller'=>'campaigns','action'=>'editcampaign',$data['CampaignProduct']['campaign_id']));
						
						
					}else{
						$this->Session->setFlash($error, 'Dialogs/top_right', array('type'=>'success'), 'top_right');	
							$this->redirect(array('controller'=>'campaigns','action'=>'editcampaign',$data['CampaignProduct']['campaign_id']));
					}
				}
				
		}
	}
	
	function add_upsale($campaign_id=null)
	{  
	
	
		$this->set('campaign_id',$campaign_id);
		$success = __('Upsell has been added successfully');
		$error = '<span style="color:red">'.__('Please fill all mendatory fields').'</span>';
			
			
		if($this->request->isPost()){
		
				$this->CampaignUpsale->Set($this->request->data);
				
				if(!$this->CampaignUpsale->upsell_validation()) // checking validation
				{
					$errorsArr = $this->CampaignUpsale->validationErrors;
				}else
				{ 
					$data=$this->request->data;
					if($this->CampaignUpsale->save($data,true)){
						$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');	
						$this->redirect(array('controller'=>'campaigns','action'=>'editcampaign',$data['CampaignUpsale']['campaign_id']));
					}else{
						$this->Session->setFlash($error, 'Dialogs/top_right', array('type'=>'success'), 'top_right');	
						$this->redirect(array('controller'=>'campaigns','action'=>'editcampaign',$data['CampaignUpsale']['campaign_id']));
					}
				}
				
		}
	}
	
	function edit_upsale($campaign_id=null,$upsale_id=null)
	{  
		$this->CampaignUpsale->id=$upsale_id;
	
		$success = __('Upsell has been edited successfully');
		$error = '<span style="color:red">'.__('Please fill all mendatory fields').'</span>';
		$this->set('campaign_id',$campaign_id);
		$this->set('upsale_id',$upsale_id);
		
		if($this->request->isPost()){
				$this->CampaignUpsale->Set($this->request->data);
				
				
				if(!$this->CampaignUpsale->upsell_validation()) // checking validation
				{
					$errorsArr = $this->CampaignUpsale->validationErrors;
				}else
				{ 
					$data=$this->request->data;
					
					if($this->CampaignUpsale->save($data,true)){
						$this->Session->setFlash($success, 'Dialogs/top_right', array('type'=>'success'), 'top_right');	
						$this->redirect(array('controller'=>'campaigns','action'=>'editcampaign',$data['CampaignUpsale']['campaign_id']));
						
						
					}else{
						$this->Session->setFlash($error, 'Dialogs/top_right', array('type'=>'success'), 'top_right');	
							$this->redirect(array('controller'=>'campaigns','action'=>'editcampaign',$data['CampaignUpsale']['campaign_id']));
					}
				}
				
		}else{
		
				$this->request->data = $this->CampaignUpsale->find('first',array('conditions'=>array('CampaignUpsale.id'=>$upsale_id))); // set data for edit 
		}
	}
	
	
}
?>
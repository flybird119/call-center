<?php
//==============================  Pushkar Soni =============================================
/* -----------------------------------------------------------------------------------------
   Call centre project - http://planetwebsolution.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2013 planetwebsolution Ltd.
  
   ---------------------------------------------------------------------------------------*/
   
App::uses('AppModel', 'Model');
class Lead extends AppModel 
{
	public $name = 'Lead';
	var $virtualFields = array('full_name' => 'CONCAT(Lead.first_name, " ", Lead.last_name)');
	//======== realations ======
	
	
	public $belongsTo = array('Campaign','Customer');
	
	//======== validation ======
	function lead_general_validation()
	{ 
		$validate1 = array(
				'first_name'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter name.',
						'last'=>true)
					),
				'last_name'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter last name.',
						'last'=>true)
					),
				'zip_code'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter pin code.',
						'last'=>true)
					)
			);
		$this->validate=$validate1;
		return $this->validates();
	}
	
	function lead_validation()
	{ 
		$validate1 = array(
				'customer_id'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please select customer.',
						'last'=>true)
					),
				'campaign_id'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please select campaign.',
						'last'=>true)
					),
				'first_name'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter last name.',
						'last'=>true)
					),
				'last_name'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter address.',
						'last'=>true)
					),
				'zip_code'=> array(
					'mustNotEmpty'=>array('rule' => 'notEmpty',
											'message'=> 'Please enter zip code.',
											'last'=>true),
									array('rule' => 'numeric',
											'message'=> 'Zip Code must be number.',
											'last'=>true)
					),
				'phone'=> array(
					'mustNotEmpty'=>array('rule' => 'notEmpty',
											'message'=> 'Please enter phone number.',
											'last'=>true),
									array('rule' => 'numeric',
											'message'=> 'phone number must be number.',
											'last'=>true),
									array('rule' => 'isUnique',
											'message'=> 'phone number already exits in our database.',
											'last'=>true)
					)
			);
		$this->validate=$validate1;
		return $this->validates();
	}
	
	
	function api_validation(){
		$validate1 = array(
				'customer_username'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> '1000 - Please enter customer username',
						'last'=>true)
					),
				'customer_password'=> array(
						'mustNotEmpty'=>array(
							'rule' => 'notEmpty',
							'message'=> '1000 - Please enter customer password'
							),
						'checkcustomer'=>array(
									'rule' => 'checkcustomerexits',
									'message'=> '1004 - Please enter valid customer detail',
									'last'=>true
									)
					),
				'campaign_id'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> '1000 - Please enter campaign id',
						'last'=>true),
						'mustNotEmpty'=>array(
							'rule' => 'isCampaginExits',
							'message'=> '1000 - Please enter valid campagin id',
						'last'=>true)
					),
				'phone'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> '1000 - Please enter phone number',
						'last'=>true),
					'mustNotEmpty'=>array(
						'rule' => array('phone','/(\d)?(\s|-)?(\()?(\d){3}(\))?/'),
						'message'=> '1008 - Please enter valid phone number',
						'last'=>true),
						'phonenumber'=>array(
									'rule' => 'phonenumberexits',
									'message'=> '1007 - Phone number already exits.',
									'last'=>true
									)
					)/*,
				'zip_code'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'please enter zip code',
						'last'=>true)
					),
				'sku'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'please enter SKU',
						'last'=>true)
					)*/
			);
		$this->validate=$validate1;
		return $this->validates();
	}
	
	
	function api_error(){
		$validate1 = array(
				'customer_username'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter customer username',
						'last'=>true)
					),
				'customer_password'=> array(
						'mustNotEmpty'=>array(
							'rule' => 'notEmpty',
							'message'=> 'Please enter customer password'
							),
						'checkcustomer'=>array(
									'rule' => 'checkcustomerexits',
									'message'=> 'please enter valid customer detail',
									'last'=>true
									)
					),
				'campaign_id'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'please enter campaign id',
						'last'=>true),
						'mustNotEmpty'=>array(
							'rule' => 'isCampaginExits',
							'message'=> 'please enter valid campagin id',
						'last'=>true)
					),
				'phone'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'please enter phone number',
						'last'=>true),
					'mustNotEmpty'=>array(
						'rule' => array('phone','/(\d)?(\s|-)?(\()?(\d){3}(\))?/'),
						'message'=> 'please enter valid phone number',
						'last'=>true),
						'phonenumber'=>array(
									'rule' => 'phonenumberexits',
									'message'=> 'phone number already exits.',
									'last'=>true
									)
					)/*,
				'zip_code'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'please enter zip code',
						'last'=>true)
					),
				'sku'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'please enter SKU',
						'last'=>true)
					)*/
			);
		$this->validate=$validate1;
		return $this->validates();
	}	
	
	function checkcustomerexits(){
		App::import('model','Customer');
   		$customer = new Customer();
		
		$rec=$customer->find('first',array('fields'=>array('password','username'),'conditions'=>'Customer.username="'.$this->data['Lead']['customer_username'].'" and Customer.password="'.$this->data['Lead']['customer_password'].'" '));
		
		if(isset($rec['Customer']['username'])){
				return true;
		}
	}
	
	function phonenumberexits(){
		
		App::import('model','Customer');
   		$customer = new Customer();
	
		App::import('model','Lead');
   		$lead = new Lead();
		
		if(isset($this->data['Lead']['customer_username'])){
			$rec=$this->Customer->findByusername($this->data['Lead']['customer_username']);
			$customer_id=$rec['Customer']['id'];
		
			$rec=$lead->find('count',array('conditions'=>'Lead.customer_id="'.$customer_id.'" and Lead.phone="'.$this->data['Lead']['phone'].'" '));
			if(!$rec){
				return true;
			}
		}
		return false;
	}
	
	
	function isCampaginExits(){
		App::import('model','Campaign');
   		$Campaign = new Campaign();
		if(!empty($this->data['Lead']['campaign_id'])){
			$total=$this->Campaign->find('count',array('conditions'=>'Campaign.id="'.$this->data['Lead']['campaign_id'].'"'));
			if($total){
				return true;
			}
			return false;
			
		}
		
	}
	
	
	//======== other functions ======
}
?>
<?php
/* -----------------------------------------------------------------------------------------
   Call centre project - http://planetwebsolution.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2013 planetwebsolution Ltd.
  
   ---------------------------------------------------------------------------------------*/
   
App::uses('AppModel', 'Model');
class Customer extends AppModel {
	public $name = 'Customer';
	
	/**
	 * model validation array
	 *
	 * @var array
	 */
	function customerValidation() { 
		$validate1 = array(
				'name'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter customer name',
						'last'=>true),
					'mustBeLonger'=>array(
						'rule' => array('minLength', 4),
						'message'=> 'name must be greater than 3 characters',
						'last'=>true),
					),
				'email'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter email',
						'last'=>true),
					'mustBeEmail'=> array(
						'rule' => array('email'),
						'message' => 'Please enter valid email',
						'last'=>true),
					'mustUnique'=>array(
						'rule' =>'isUnique',
						'message' =>'This email is already registered',
						)
					),
				'website_url'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter website url',
						'last'=>true),
					'mustBeLonger'=>array(
						'rule' => array('url'),
						'message'=> 'Please enter valid website address',
						'last'=>true),
					),
				'username'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter username',
						'last'=>true),
						'mustUnique'=>array(
						'rule' =>'isUnique',
						'message' =>'This username is already exits.',
						)
					),
				'password'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter password',
						'last'=>true)
					)
					
			);
		$this->validate=$validate1;
		return $this->validates();
	}
	
	

	
}
?>
<?php
App::uses('AppModel', 'Model');

class Support extends AppModel {
	var $name="Support";
	
		function support_validation()
		{ 
		$validate1 = array(
				'support_message'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter message.',
						'last'=>true)
					)/*,
				'support_emailaddress'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter email address.',
						'last'=>true),
					'email'=>array(
						'rule' => 'email',
						'message'=> 'Please enter valid e-mail address.',
						'last'=>true)
					)*/
			);
		$this->validate=$validate1;
		return $this->validates();
	}
}
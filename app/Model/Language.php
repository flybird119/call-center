<?php
/* -----------------------------------------------------------------------------------------
   VamCart - http://vamcart.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2011 VamSoft Ltd.
   License - http://vamcart.com/license.html
   ---------------------------------------------------------------------------------------*/
App::uses('AppModel', 'Model');
class Language extends AppModel {
	public $name = 'Language';
	
	
	function languageValidation()
	{

		$validate1=array('name'=>array('allowEmpty'=>array('rule'       => 'notEmpty',
															'message'    => 'Please enter name.',
															'allowEmpty' => false),
										'isUnique'=>array('rule'       => 'isUnique',
															'message'    => 'Name Must be unique.',
															'allowEmpty' => false)
										),
						'iso_code_2'=>array('allowEmpty'=>array('rule'       => 'notEmpty',
															'message'    => 'Please enter ISO code.',
															'allowEmpty' => false),
										'isUnique'=>array('rule'       => 'isUnique',
															'message'    => 'ISO code Must be unique',
															'allowEmpty' => false)
										),
						
						);
						
			$this->validate=$validate1;
			return $this->validates();
	}
	
}
?>
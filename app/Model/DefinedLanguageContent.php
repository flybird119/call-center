<?php
/* -----------------------------------------------------------------------------------------
   VamCart - http://vamcart.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2011 VamSoft Ltd.
   License - http://vamcart.com/license.html
   ---------------------------------------------------------------------------------------*/
App::uses('AppModel', 'Model');
class DefinedLanguageContent extends AppModel {
	public $name = 'DefinedLanguageContent';
	public $hasOne = array('DefinedLanguage');
	
	function languageValidation()
	{
		
		$validate1=array( 
			'content' => array(
				'notEmpty' => array(
					'rule'    => 'notEmpty',
					'message' => 'Please enter title.'
				),
				'isUnique' => array(
					'rule'    => 'isUnique',
					'message' => 'This Title is already exits in our database.'
				)
			)
			
		);
		$this->validate=$validate1;
		return $this->validates();
	}
}
?>
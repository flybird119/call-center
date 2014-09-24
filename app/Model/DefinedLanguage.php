<?php
/* -----------------------------------------------------------------------------------------
   VamCart - http://vamcart.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2011 VamSoft Ltd.
   License - http://vamcart.com/license.html
   ---------------------------------------------------------------------------------------*/
App::uses('AppModel', 'Model');
class DefinedLanguage extends AppModel {
	public $name = 'DefinedLanguage';
	public $belongsTo = array('Language','DefinedLanguageContent');
	
	function staticcontentValidation()
	{
		
		$validate1=array('value'=>array('allowEmpty'=>array('rule'       => 'notEmpty',
															'message'    => 'Please enter name.',
															'allowEmpty' => false)
											)
						);
		$this->validate=$validate1;
		return $this->validates();
	}
}
?>
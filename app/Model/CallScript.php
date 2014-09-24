<?php
/* -----------------------------------------------------------------------------------------
   Call centre project - http://planetwebsolution.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2013 planetwebsolution Ltd.
  
   ---------------------------------------------------------------------------------------*/
App::uses('AppModel', 'Model');
class CallScript extends AppModel 
{
	public $name = 'CallScript';

	
	 public $hasMany = array(
        'Rebuttal' => array(
            'className'  => 'Rebuttal',
            'dependent'      => true
        )
    );
	
	 public $belongsTo = array('Campaign');
		
		
	
	
	
	function callscript_validation () { 
		$validate1 = array(
				'name'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter name',
						'last'=>true)
					),
				'script'=> array(
					'mustNotEmpty'=>array(
						'rule' => 'notEmpty',
						'message'=> 'Please enter script',
						'last'=>true)
					)	
			);
		$this->validate=$validate1;
		return $this->validates();
	}
}
?>
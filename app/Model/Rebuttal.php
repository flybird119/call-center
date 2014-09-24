<?php
/* -----------------------------------------------------------------------------------------
   Call centre project - http://planetwebsolution.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2013 planetwebsolution Ltd.
  
   ---------------------------------------------------------------------------------------*/
   
App::uses('AppModel', 'Model');
class Rebuttal extends AppModel 
{
	public $name = 'Rebuttal';
	 public $belongsTo = array(
        'CallScript' => array(
            'className'    => 'CallScript',
            'foreignKey'   => 'call_script_id'
        )
    );
		

}
?>
<?php
//==============================  Pushkar Soni =============================================
/* -----------------------------------------------------------------------------------------
   Call centre project - http://planetwebsolution.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2013 planetwebsolution Ltd.
  ---------------------------------------------------------------------------------------*/
   
App::uses('AppModel', 'Model');
class OrderUpsale extends AppModel 
{
	public $name = 'OrderUpsale';
	public $useTable = 'order_upsales';
	
	//======== realations ======
	//public $hasOne = array('Campaign');
	//public $belongsTo = array('Campaign');
	
	
}
?>